<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Projet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use App\Mail\InvitationProjet;

class ProjetTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function un_utilisateur_peut_inviter_un_membre()
    {
        Mail::fake();

        $admin = User::factory()->create();
        $user = User::factory()->create();
        $projet = Projet::factory()->create(['createur_id' => $admin->id]);

        $this->actingAs($admin)
             ->post(route('projets.inviter', $projet), [
                 'email' => $user->email,
                 'role' => 'membre',
             ])
             ->assertSessionHas('success');

        // Vérifier que l'utilisateur est ajouté au projet
        $this->assertTrue($projet->membres->contains($user));

        // Vérifier que l'email a été envoyé
        Mail::assertSent(InvitationProjet::class);
    }
}
