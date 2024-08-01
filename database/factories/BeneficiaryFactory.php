<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Beneficiary>
 */
class BeneficiaryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $randomTimestamp = mt_rand(Carbon::create(1934, 1, 1)->timestamp, Carbon::create(2001, 12, 31)->timestamp);
        $birthdate = Carbon::createFromTimestamp($randomTimestamp)->format('Y-m-d');

        $last_name = fake()->lastName();
        $type_of_id = fake()->randomElement(['Driver\'s License', 'National ID', 'TIN ID', 'PhilHealth ID', 'Postal ID', 'NBI Clearance']);
        $id_number = $this->getIdNumber($type_of_id);
        $civil_status = fake()->randomElement(['Single', 'Married']);
        $sex = fake()->randomElement(['Male', 'Female']);

        return [
            'batches_id' => 1,
            'first_name' => fake()->firstName(strtolower($sex)),
            'middle_name' => fake()->optional(0.8, "-")->lastName(),
            'last_name' => $last_name,
            'extension_name' => fake()->optional(0.1, "-")->suffix(),
            'birthdate' => $birthdate,
            'barangay_name' => '',
            'contact_num' => fake()->numerify('+639#########'),
            'occupation' => 'None',
            'avg_monthly_income' => '-',
            'city_municipality' => 'Davao City',
            'province' => 'Davao del Sur',
            'district' => '',
            'type_of_id' => $type_of_id,
            'id_number' => $id_number,
            'e_payment_acc_num' => '-',
            'beneficiary_type' => 'Underemployed',
            'sex' => $sex,
            'civil_status' => $civil_status,
            'age' => $this->age($birthdate),
            'dependent' => 'None',
            'self_employment' => 'No',
            'skills_training' => 'None',
            'is_pwd' => fake()->optional(0.05, "No")->randomElement(['Yes']),
            'is_senior_citizen' => $this->checkSeniorCitizen($this->age($birthdate)),
            'spouse_first_name' => $this->checkSpouse($civil_status, $sex, 'first'),
            'spouse_middle_name' => $this->checkSpouse($civil_status, $sex, 'middle'),
            'spouse_last_name' => $this->checkSpouse($civil_status, $sex, 'last', $last_name),
            'spouse_extension_name' => $this->checkSpouse($civil_status, $sex, 'ext'),
        ];
    }

    protected function checkSeniorCitizen($age)
    {
        if ($age >= 60) {
            return 'Yes';
        }
        return 'No';
    }

    protected function checkSpouse($civil_status, $sex, $nameType, $last_name = null)
    {
        $name = '-';
        if (strtolower($civil_status) == 'married') {
            switch ($nameType) {
                case 'first':
                    if (strtolower($sex) == 'male') {
                        return $name = fake()->firstName('male');
                    } else if (strtolower($sex) == 'female') {
                        return $name = fake()->firstName('male');
                    }
                case 'middle':
                    return $name = fake()->optional(0.8, "-")->lastName();
                case 'last':
                    return $name = $last_name;
                case 'ext':
                    return $name = fake()->optional(0.1, "-")->suffix();
            }
        }
        return $name;
    }
    protected function getIdNumber($type_of_id)
    {
        $id_number = '';
        // ['Driver\'s License', 'National ID', 'TIN ID', 'PhilHealth ID', 'Postal ID', 'NBI Clearance']
        switch ($type_of_id) {
            case 'Driver\'s License':
                $id_number = fake()->bothify('?##-##-######');
                break;
            case 'National ID':
                $id_number = fake()->bothify('####-###-####-#');
                break;
            case 'TIN ID':
                $id_number = fake()->bothify('###-###-###-###');
                break;
            case 'PhilHealth ID':
                $id_number = fake()->bothify('##-#########-#');
                break;
            case 'Postal ID':
                $id_number = fake()->bothify('############-?');
                break;
            case 'NBI Clearance':
                $id_number = fake()->bothify('????##?##?-?####?###');
                break;
        }
        return strtoupper($id_number);
    }
    protected function age($birthdate)
    {
        return Carbon::parse($birthdate)->age;
    }
}
