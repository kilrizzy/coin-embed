<?php

namespace Database\Factories;

use App\Models\Block;
use Illuminate\Database\Eloquent\Factories\Factory;

class BlockFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Block::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'block_id' => $this->faker->uuid,
            'payment_method_key' => $this->faker->randomElement(['nano']),
            'transaction_id' => null,
            'status' => null,
            'data' => '{"block_account":"nano_3mwrcgxsiqx5qhfms9un167qq17bn54xf5iah15ge96k7yzegpsycthwdbef","amount":"1000000000000000000000000","balance":"14682191317279999400000000000000","height":"99","local_timestamp":"1605490339","confirmed":"true","contents":{"type":"state","account":"nano_3mwrcgxsiqx5qhfms9un167qq17bn54xf5iah15ge96k7yzegpsycthwdbef","previous":"46B1013EEADF6BECD978264F5C9B9D063BB15B4845FE7C613E2806B4B8D0A611","representative":"nano_1bananobjcrqugm87e8p3kxkhy7d1bzkty53n889iyunm83cp14rb9fin78p","balance":"14682191317279999400000000000000","link":"E78138FBA372740BF2A2410F3F526084D8EEDD2165D5181CE61B87470B03F2A7","link_as_account":"nano_3sw395xt8wmn3hsc6iah9xb8338rxugk4sgo51gge8w9aw7i9wo94bzzk71y","signature":"31064837356EEFC276FC556A17BF1C8F6F045AC29B816CA24261ED1B9D7385661B04F037F698311CEA5217E6EF055F89D8B93FDEFA659153411C81E2864D9308","work":"a11fba29e22ffb9a"},"subtype":"send"}',
            'completed_at' => $this->faker->dateTime(),
        ];
    }
}
