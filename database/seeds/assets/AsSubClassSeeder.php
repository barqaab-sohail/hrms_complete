<?php

use Illuminate\Database\Seeder;

class AsSubClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       
       DB::table('as_sub_classes')->delete();

       $asSubClasses = array(
			array('as_class_id' => 1, 'name' => 'Car'),
			array('as_class_id' => 1, 'name' => 'Motor Bike'),
			array('as_class_id' => 1, 'name' => 'Bicycle'),
			array('as_class_id' => 2, 'name' => 'Window AC'),
			array('as_class_id' => 2, 'name' => 'Split AC'),
			array('as_class_id' => 2, 'name' => 'Fridge'),
			array('as_class_id' => 2, 'name' => 'Freezer'),
			array('as_class_id' => 2, 'name' => 'Water Dispenser'),
			array('as_class_id' => 2, 'name' => 'Microwave Oven'),
			array('as_class_id' => 2, 'name' => 'Camera'),
			array('as_class_id' => 2, 'name' => 'Electric Geyser'),
			array('as_class_id' => 2, 'name' => 'Ceiling Fan'),
			array('as_class_id' => 2, 'name' => 'Bracket Fan'),
			array('as_class_id' => 2, 'name' => 'Exhaust Fan'),
			array('as_class_id' => 2, 'name' => 'Electric Heater'),
			array('as_class_id' => 2, 'name' => 'UPS'),
			array('as_class_id' => 2, 'name' => 'Computer'),
			array('as_class_id' => 2, 'name' => 'Laptop'),
			array('as_class_id' => 2, 'name' => 'Pritner'),
			array('as_class_id' => 2, 'name' => 'Photocopier'),
			array('as_class_id' => 2, 'name' => 'Scanner'),
			array('as_class_id' => 2, 'name' => 'External Hard Disk'),
			array('as_class_id' => 3, 'name' => 'Computer Table'),
			array('as_class_id' => 3, 'name' => 'Office Table'),
			array('as_class_id' => 3, 'name' => 'Visiting Chair'),
			array('as_class_id' => 3, 'name' => 'Executive Office Chair'),
			array('as_class_id' => 3, 'name' => 'Computer Chair'),
			array('as_class_id' => 3, 'name' => 'Sofa'),
			array('as_class_id' => 3, 'name' => 'Steel Cabinet'),
			array('as_class_id' => 3, 'name' => 'Almirah'),

			
		);

       DB::table('as_sub_classes')->insert($asSubClasses);
    }
}
