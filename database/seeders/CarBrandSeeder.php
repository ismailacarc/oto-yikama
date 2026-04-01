<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CarBrand;
use App\Models\CarModel;

class CarBrandSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'Toyota'      => ['Corolla', 'Yaris', 'Camry', 'RAV4', 'C-HR', 'Land Cruiser', 'Hilux', 'Auris', 'Avensis', 'Prius', 'Verso'],
            'Volkswagen'  => ['Golf', 'Passat', 'Polo', 'Tiguan', 'Touareg', 'Caddy', 'Transporter', 'Arteon', 'T-Cross', 'T-Roc', 'Touran', 'Amarok'],
            'Mercedes'    => ['A Serisi', 'B Serisi', 'C Serisi', 'E Serisi', 'S Serisi', 'GLA', 'GLC', 'GLE', 'GLS', 'CLA', 'CLS', 'Vito', 'Sprinter', 'G Serisi'],
            'BMW'         => ['1 Serisi', '2 Serisi', '3 Serisi', '4 Serisi', '5 Serisi', '7 Serisi', 'X1', 'X2', 'X3', 'X4', 'X5', 'X6', 'X7', 'M3', 'M5'],
            'Audi'        => ['A1', 'A3', 'A4', 'A5', 'A6', 'A7', 'A8', 'Q2', 'Q3', 'Q5', 'Q7', 'Q8', 'TT', 'RS3', 'RS6', 'e-tron'],
            'Renault'     => ['Clio', 'Megane', 'Symbol', 'Fluence', 'Laguna', 'Scenic', 'Kadjar', 'Captur', 'Duster', 'Talisman', 'Zoe', 'Master', 'Trafic'],
            'Fiat'        => ['Egea', 'Tipo', 'Punto', 'Stilo', 'Bravo', 'Linea', 'Doblo', 'Ducato', 'Fiorino', 'Panda', '500', 'Albea'],
            'Ford'        => ['Focus', 'Fiesta', 'Mondeo', 'Mustang', 'Puma', 'Kuga', 'EcoSport', 'Explorer', 'Ranger', 'Transit', 'Transit Connect'],
            'Hyundai'     => ['i10', 'i20', 'i30', 'Elantra', 'Sonata', 'Tucson', 'Santa Fe', 'Kona', 'Ioniq', 'H-1', 'Accent'],
            'Kia'         => ['Picanto', 'Rio', 'Ceed', 'Sportage', 'Sorento', 'Stinger', 'Niro', 'EV6', 'Carnival', 'Soul', 'Stonic'],
            'Honda'       => ['Civic', 'Accord', 'Jazz', 'CR-V', 'HR-V', 'FR-V', 'Legend', 'Insight'],
            'Nissan'      => ['Micra', 'Note', 'Juke', 'Qashqai', 'X-Trail', 'Navara', 'Leaf', 'Almera', 'Primera', 'Pathfinder'],
            'Opel'        => ['Corsa', 'Astra', 'Insignia', 'Mokka', 'Crossland', 'Grandland', 'Zafira', 'Meriva', 'Combo', 'Vivaro'],
            'Peugeot'     => ['208', '301', '308', '508', '2008', '3008', '5008', 'Partner', 'Expert', 'Boxer', '206', '207'],
            'Citroen'     => ['C1', 'C2', 'C3', 'C4', 'C5', 'C3 Aircross', 'Berlingo', 'Jumpy', 'SpaceTourer'],
            'Skoda'       => ['Fabia', 'Octavia', 'Superb', 'Kodiaq', 'Karoq', 'Kamiq', 'Scala', 'Enyaq'],
            'Seat'        => ['Ibiza', 'Leon', 'Toledo', 'Altea', 'Ateca', 'Arona', 'Tarraco'],
            'Volvo'       => ['S40', 'S60', 'S80', 'S90', 'V40', 'V60', 'V70', 'V90', 'XC40', 'XC60', 'XC70', 'XC90'],
            'Land Rover'  => ['Defender', 'Discovery', 'Discovery Sport', 'Freelander', 'Range Rover', 'Range Rover Sport', 'Range Rover Evoque'],
            'Jeep'        => ['Renegade', 'Compass', 'Cherokee', 'Grand Cherokee', 'Wrangler'],
            'Porsche'     => ['911', 'Cayenne', 'Macan', 'Panamera', 'Taycan', 'Boxster', 'Cayman'],
            'Subaru'      => ['Forester', 'Outback', 'Impreza', 'Legacy', 'XV', 'WRX'],
            'Mazda'       => ['Mazda2', 'Mazda3', 'Mazda6', 'CX-3', 'CX-5', 'CX-30', 'MX-5'],
            'Mitsubishi'  => ['Lancer', 'Galant', 'Eclipse Cross', 'Outlander', 'ASX', 'L200', 'Colt'],
            'Suzuki'      => ['Swift', 'Vitara', 'S-Cross', 'Jimny', 'SX4', 'Alto', 'Ignis', 'Baleno'],
            'Dacia'       => ['Sandero', 'Logan', 'Duster', 'Lodgy', 'Dokker', 'Spring', 'Jogger'],
            'Alfa Romeo'  => ['147', '156', '159', 'Giulia', 'Stelvio', 'Tonale', 'Giulietta', 'MiTo'],
            'Lexus'       => ['IS', 'ES', 'GS', 'LS', 'NX', 'RX', 'GX', 'LX', 'UX', 'LC'],
            'Tesla'       => ['Model S', 'Model 3', 'Model X', 'Model Y', 'Cybertruck'],
            'Jaguar'      => ['XE', 'XF', 'XJ', 'E-Pace', 'F-Pace', 'I-Pace', 'F-Type'],
            'TOGG'        => ['T10X', 'T10F'],
            'Tofaş'       => ['Şahin', 'Doğan', 'Kartal', 'Tempra', 'Marea', 'Albea', 'Palio'],
            'Maserati'    => ['Ghibli', 'Quattroporte', 'Levante', 'GranTurismo'],
            'Lamborghini' => ['Huracán', 'Urus', 'Revuelto'],
            'Ferrari'     => ['Roma', 'Portofino', 'F8', 'SF90', '296'],
        ];

        foreach ($data as $brandName => $models) {
            $brand = CarBrand::firstOrCreate(['name' => $brandName]);
            foreach ($models as $modelName) {
                CarModel::firstOrCreate([
                    'car_brand_id' => $brand->id,
                    'name'         => $modelName,
                ]);
            }
        }
    }
}
