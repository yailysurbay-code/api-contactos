<?php

use App\Models\Contact;
use App\Models\User;

test('un usuario puede tener muchos contactos', function () {
    $user = User::factory()->create();

    Contact::factory()->count(3)->create([
        'user_id' => $user->id,
    ]);

    expect($user->contacts()->count())->toBe(3);
});

test('un contacto pertenece a un usuario', function () {
    $user = User::factory()->create();

    $contact = Contact::factory()->create([
        'user_id' => $user->id,
    ]);

    expect($contact->user->id)->toBe($user->id);
});

test('un usuario puede ver su lista de contactos', function () {
    $user = User::factory()->create();

    Contact::factory()->count(3)->create([
        'user_id' => $user->id,
    ]);

    $token = $user->createToken('test-token')->plainTextToken;

    $response = $this->withToken($token)
        ->getJson('/api/v1/contacts');

    $response->assertStatus(200)
        ->assertJsonCount(3, 'datos');
});

test('un usuario no puede ver los contactos de otro usuario', function () {
    $usuario1 = User::factory()->create();
    $usuario2 = User::factory()->create();

    Contact::factory()->count(2)->create([
        'user_id' => $usuario1->id,
    ]);

    Contact::factory()->count(3)->create([
        'user_id' => $usuario2->id,
    ]);

    $token = $usuario1->createToken('test-token')->plainTextToken;

    $response = $this->withToken($token)
        ->getJson('/api/v1/contacts');

    $response->assertStatus(200)
        ->assertJsonCount(2, 'datos');
});

test('un usuario no puede consultar un contacto de otro usuario', function () {
    $usuario1 = User::factory()->create();
    $usuario2 = User::factory()->create();

    $contacto = Contact::factory()->create([
        'user_id' => $usuario2->id,
    ]);

    $token = $usuario1->createToken('test-token')->plainTextToken;

    $response = $this->withToken($token)
        ->getJson('/api/v1/contacts/' . $contacto->id);

    $response->assertStatus(404);
});

test('un usuario puede crear un contacto correctamente', function () {
    $user = User::factory()->create();

    $token = $user->createToken('test-token')->plainTextToken;

    $response = $this->withToken($token)
        ->postJson('/api/v1/contacts', [
            'name' => 'Maria Perez',
            'email' => 'maria@gmail.com',
            'address' => 'Fonseca, La Guajira',
            'phone_number' => '3001234567',
        ]);

    $response->assertStatus(201)
        ->assertJsonPath('datos.name', 'Maria Perez');

    $this->assertDatabaseHas('contacts', [
        'name' => 'Maria Perez',
        'email' => 'maria@gmail.com',
        'user_id' => $user->id,
    ]);
});

test('no permite crear un contacto sin los campos obligatorios', function () {
    $user = User::factory()->create();

    $token = $user->createToken('test-token')->plainTextToken;

    $response = $this->withToken($token)
        ->postJson('/api/v1/contacts', []);

    $response->assertStatus(422)
        ->assertJsonValidationErrors([
            'name',
            'email',
            'address',
            'phone_number',
        ]);
});

test('un usuario puede consultar un contacto propio', function () {
    $user = User::factory()->create();

    $contacto = Contact::factory()->create([
        'user_id' => $user->id,
    ]);

    $token = $user->createToken('test-token')->plainTextToken;

    $response = $this->withToken($token)
        ->getJson('/api/v1/contacts/' . $contacto->id);

    $response->assertStatus(200)
        ->assertJsonPath('datos.id', $contacto->id);
});

test('un usuario puede actualizar su contacto', function () {
    $user = User::factory()->create();

    $contacto = Contact::factory()->create([
        'user_id' => $user->id,
    ]);

    $token = $user->createToken('test-token')->plainTextToken;

    $response = $this->withToken($token)
        ->putJson('/api/v1/contacts/' . $contacto->id, [
            'name' => 'Maria Actualizada',
            'phone_number' => '3009876543',
        ]);

    $response->assertStatus(200)
        ->assertJsonPath('datos.name', 'Maria Actualizada');

    $this->assertDatabaseHas('contacts', [
        'id' => $contacto->id,
        'name' => 'Maria Actualizada',
    ]);
});