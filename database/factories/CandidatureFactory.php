<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CandidatureFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id'          => User::factory(),
            'entreprise'       => $this->faker->company(),
            'poste'            => $this->faker->jobTitle(),
            'url_offre'        => $this->faker->url(),
            'statut'           => $this->faker->randomElement([
                'envoyee', 'relance', 'entretien', 'offre', 'refus'
            ]),
            'priorite'         => $this->faker->randomElement([
                'faible', 'moyenne', 'haute'
            ]),
            'notes'            => $this->faker->sentence(),
            'date_candidature' => $this->faker->date(),
            'fichier'          => null,
        ];
    }
}