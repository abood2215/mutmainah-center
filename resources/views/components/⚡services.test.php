<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('services')
        ->assertStatus(200);
});
