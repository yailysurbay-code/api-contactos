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