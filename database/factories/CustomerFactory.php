<?php

namespace Database\Factories;

use App\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Ported from the 5.2 database/factories/ModelFactory.php, which used the
 * $factory->define() style removed in Laravel 8.
 */
class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition(): array
    {
        $type = $this->faker->randomElement(['company', 'person']);

        return [
            'name' => $this->faker->firstName(),
            'surname' => $this->faker->lastName(),
            'company' => $type === 'company' ? $this->faker->company() : null,
            'type' => $type,
            'email1' => $this->faker->safeEmail(),
            'email2' => $this->faker->safeEmail(),
            'website' => $this->faker->url(),
            'phone' => $this->faker->phoneNumber(),
            'mobile' => $this->faker->phoneNumber(),
            'vatid' => 'VAT' . $this->faker->numerify('#####'),
            'taxid' => 'TAX' . $this->faker->numerify('#####'),
            'street1' => $this->faker->streetAddress(),
            'street2' => $this->faker->streetAddress(),
            'city' => $this->faker->city(),
            'state' => $this->faker->city(),
            'zipcode' => $this->faker->postcode(),
            'country' => $this->faker->country(),
            // notes is a varchar(255); paragraph() occasionally exceeds that and
            // made seeding fail at random.
            'notes' => $this->faker->text(200),
            'customer_group_id' => rand(1, 3),
            'enabled' => true,
        ];
    }
}
