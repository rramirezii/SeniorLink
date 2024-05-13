<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('super_admin')->insert([
            ['id' => 1, 'name' => 'Super_Admin 1', 'username' => 'admin1', 'password' => "password1"],
            ['id' => 2, 'name' => 'Super_Admin 2', 'username' => 'admin2', 'password' => "password2"],
            ['id' => 3, 'name' => 'Super_Admin 3', 'username' => 'admin3', 'password' => "password3"],
            ['id' => 4, 'name' => 'Super_Admin 4', 'username' => 'admin4', 'password' => "password4"],
        ]);

        DB::table('town')->insert([
            ['id' => 1, 'name' => 'Town 1', 'zip_code' => 12345, 'username' => 't12345', 'password' => "password1"],
            ['id' => 2, 'name' => 'Town 2', 'zip_code' => 23456, 'username' => 't23456', 'password' => "password2"],
            ['id' => 3, 'name' => 'Town 3', 'zip_code' => 34567, 'username' => 't34567', 'password' => "password3"],
            ['id' => 4, 'name' => 'Town 4', 'zip_code' => 45678, 'username' => 't45678', 'password' => "password4"],
            ['id' => 5, 'name' => 'Town 5', 'zip_code' => 56789, 'username' => 't56789', 'password' => "password5"],
        ]);

        DB::table('barangay')->insert([
            ['id' => 26, 'name' => 'Barangay 1', 'town_id' => 5, 'password' => "password1", 'username' => 'b56789barangay_1'],
            ['id' => 27, 'name' => 'Barangay 2', 'town_id' => 5, 'password' => "password2", 'username' => 'b56789barangay_2'],
            ['id' => 28, 'name' => 'Barangay 3', 'town_id' => 5, 'password' => "password3", 'username' => 'b56789barangay_3'],
            ['id' => 29, 'name' => 'Barangay 4', 'town_id' => 5, 'password' => "password4", 'username' => 'b56789barangay_4'],
            ['id' => 30, 'name' => 'Barangay 5', 'town_id' => 5, 'password' => "password5", 'username' => 'b56789barangay_5'],
            ['id' => 31, 'name' => 'Barangay 6', 'town_id' => 1, 'password' => "password6", 'username' => 'b12345barangay_6'],
            ['id' => 32, 'name' => 'Barangay 7', 'town_id' => 1, 'password' => "password7", 'username' => 'b12345barangay_7'],
            ['id' => 33, 'name' => 'Barangay 8', 'town_id' => 1, 'password' => "password8", 'username' => 'b12345barangay_8'],
            ['id' => 34, 'name' => 'Barangay 9', 'town_id' => 1, 'password' => "password9", 'username' => 'b12345barangay_9'],
            ['id' => 35, 'name' => 'Barangay 10', 'town_id' => 1, 'password' => "password10", 'username' => 'b12345barangay_10'],
            ['id' => 36, 'name' => 'Barangay 11', 'town_id' => 2, 'password' => "password11", 'username' => 'b23456barangay_11'],
            ['id' => 37, 'name' => 'Barangay 12', 'town_id' => 2, 'password' => "password12", 'username' => 'b23456barangay_12'],
            ['id' => 38, 'name' => 'Barangay 13', 'town_id' => 2, 'password' => "password13", 'username' => 'b23456barangay_13'],
            ['id' => 39, 'name' => 'Barangay 14', 'town_id' => 2, 'password' => "password14", 'username' => 'b23456barangay_14'],
            ['id' => 40, 'name' => 'Barangay 15', 'town_id' => 2, 'password' => "password15", 'username' => 'b23456barangay_15'],
            ['id' => 41, 'name' => 'Barangay 16', 'town_id' => 3, 'password' => "password16", 'username' => 'b34567barangay_16'],
            ['id' => 42, 'name' => 'Barangay 17', 'town_id' => 3, 'password' => "password17", 'username' => 'b34567barangay_17'],
            ['id' => 43, 'name' => 'Barangay 18', 'town_id' => 3, 'password' => "password18", 'username' => 'b34567barangay_18'],
            ['id' => 44, 'name' => 'Barangay 19', 'town_id' => 3, 'password' => "password19", 'username' => 'b34567barangay_19'],
            ['id' => 45, 'name' => 'Barangay 20', 'town_id' => 3, 'password' => "password20", 'username' => 'b34567barangay_20'],
            ['id' => 46, 'name' => 'Barangay 21', 'town_id' => 4, 'password' => "password21", 'username' => 'b45678barangay_21'],
            ['id' => 47, 'name' => 'Barangay 22', 'town_id' => 4, 'password' => "password22", 'username' => 'b45678barangay_22'],
            ['id' => 48, 'name' => 'Barangay 23', 'town_id' => 4, 'password' => "password24", 'username' => 'b45678barangay_23'],
            ['id' => 49, 'name' => 'Barangay 24', 'town_id' => 4, 'password' => "password23", 'username' => 'b45678barangay_24'],
            ['id' => 50, 'name' => 'Barangay 25', 'town_id' => 4, 'password' => "password25", 'username' => 'b45678barangay_25'],
        ]);

        DB::table('establishment')->insert([
            ['id' => 1, 'name' => 'Establishment 1', 'code' => 'EST001', 'address' => '123 Main St, Cityville', 'username' => 'eest001', 'password' => "password1"],
            ['id' => 2, 'name' => 'Establishment 2', 'code' => 'EST002', 'address' => '456 Elm St, Townsville', 'username' => 'eest002', 'password' => "password2"],
            ['id' => 3, 'name' => 'Establishment 3', 'code' => 'EST003', 'address' => '789 Oak St, Villageton', 'username' => 'eest003', 'password' => "password3"],
            ['id' => 4, 'name' => 'Establishment 4', 'code' => 'EST004', 'address' => '101 Maple St, Hamletown', 'username' => 'eest004', 'password' => "password4"],
            ['id' => 5, 'name' => 'Establishment 5', 'code' => 'EST005', 'address' => '202 Pine St, Countryside', 'username' => 'eest005', 'password' => "password5"],
            ['id' => 6, 'name' => 'Establishment 6', 'code' => 'EST006', 'address' => '303 Cedar St, Suburbia', 'username' => 'eest006', 'password' => "password6"],
            ['id' => 7, 'name' => 'Establishment 7', 'code' => 'EST007', 'address' => '404 Birch St, Ruralville', 'username' => 'eest007', 'password' => "password7"],
            ['id' => 8, 'name' => 'Establishment 8', 'code' => 'EST008', 'address' => '505 Walnut St, Farmland', 'username' => 'eest008', 'password' => "password8"],
            ['id' => 9, 'name' => 'Establishment 9', 'code' => 'EST009', 'address' => '606 Spruce St, Hilltop', 'username' => 'eest009', 'password' => "password9"],
            ['id' => 10, 'name' => 'Establishment 10', 'code' => 'EST010', 'address' => '707 Fir St, Mountainside', 'username' => 'eest010', 'password' => "password10"],
        ]);

        DB::table('senior')->insert([
            ['id' => 51, 'osca_id' => '3456789', 'fname' => 'Pedro', 'mname' => 'Aquino', 'lname' => 'Torres', 'barangay_id' => 31, 'birthdate' => '1948-12-03', 'contact_number' => '09345678901', 'username' => 's567893456789'],
            ['id' => 52, 'osca_id' => '4567890', 'fname' => 'Luz', 'mname' => 'Cruz', 'lname' => 'Santiago', 'barangay_id' => 31, 'birthdate' => '1951-03-20', 'contact_number' => '09456789012', 'username' => 's567894567890'],
            ['id' => 53, 'osca_id' => '5678901', 'fname' => 'Jose', 'mname' => 'Mendoza', 'lname' => 'Garcia', 'barangay_id' => 32, 'birthdate' => '1949-07-28', 'contact_number' => '09567890123', 'username' => 's567895678901'],
            ['id' => 54, 'osca_id' => '6789012', 'fname' => 'Anna', 'mname' => 'Lopez', 'lname' => 'Rivera', 'barangay_id' => 32, 'birthdate' => '1953-11-05', 'contact_number' => '09678901234', 'username' => 's567896789012'],
            ['id' => 55, 'osca_id' => '7890123', 'fname' => 'Ramon', 'mname' => 'Martinez', 'lname' => 'Perez', 'barangay_id' => 33, 'birthdate' => '1947-09-14', 'contact_number' => '09789012345', 'username' => 's567897890123'],
            ['id' => 56, 'osca_id' => '8901234', 'fname' => 'Sofia', 'mname' => 'Hernandez', 'lname' => 'Villanueva', 'barangay_id' => 33, 'birthdate' => '1950-04-25', 'contact_number' => '09890123456', 'username' => 's567898901234'],
            ['id' => 57, 'osca_id' => '9012345', 'fname' => 'Cesar', 'mname' => 'Diaz', 'lname' => 'Cruz', 'barangay_id' => 34, 'birthdate' => '1945-06-30', 'contact_number' => '09901234567', 'username' => 's567899012345'],
            ['id' => 58, 'osca_id' => '1234568', 'fname' => 'Elena', 'mname' => 'Torres', 'lname' => 'Castillo', 'barangay_id' => 34, 'birthdate' => '1954-01-17', 'contact_number' => '09112345678', 'username' => 's567891234568'],
            ['id' => 59, 'osca_id' => '2345679', 'fname' => 'Angel', 'mname' => 'Sanchez', 'lname' => 'Romero', 'barangay_id' => 35, 'birthdate' => '1946-10-12', 'contact_number' => '09223456789', 'username' => 's567892345679'],
            ['id' => 60, 'osca_id' => '3456780', 'fname' => 'Carmen', 'mname' => 'Ramirez', 'lname' => 'Gomez', 'barangay_id' => 35, 'birthdate' => '1952-02-28', 'contact_number' => '09334567890', 'username' => 's567893456780'],
            ['id' => 61, 'osca_id' => '4567891', 'fname' => 'Antonio', 'mname' => 'Fernandez', 'lname' => 'Lopez', 'barangay_id' => 36, 'birthdate' => '1948-11-09', 'contact_number' => '09445678901', 'username' => 's123454567891'],
            ['id' => 62, 'osca_id' => '5678902', 'fname' => 'Marian', 'mname' => 'Vega', 'lname' => 'Gutierrez', 'barangay_id' => 36, 'birthdate' => '1950-07-07', 'contact_number' => '09556789012', 'username' => 's123455678902'],
            ['id' => 63, 'osca_id' => '6789013', 'fname' => 'Rosa', 'mname' => 'Peralta', 'lname' => 'Ramos', 'barangay_id' => 37, 'birthdate' => '1949-03-22', 'contact_number' => '09667890123', 'username' => 's123456789013'],
            ['id' => 64, 'osca_id' => '7890124', 'fname' => 'Juanito', 'mname' => 'Cruz', 'lname' => 'Santos', 'barangay_id' => 37, 'birthdate' => '1953-12-11', 'contact_number' => '09778901234', 'username' => 's123457890124'],
            ['id' => 65, 'osca_id' => '8901235', 'fname' => 'Marcelo', 'mname' => 'Gonzalez', 'lname' => 'Serrano', 'barangay_id' => 38, 'birthdate' => '1947-08-18', 'contact_number' => '09889012345', 'username' => 's123458901235'],
            ['id' => 66, 'osca_id' => '9012346', 'fname' => 'Elsa', 'mname' => 'Torres', 'lname' => 'Rojas', 'barangay_id' => 38, 'birthdate' => '1951-05-05', 'contact_number' => '09990123456', 'username' => 's123459012346'],
            ['id' => 67, 'osca_id' => '1234569', 'fname' => 'Julia', 'mname' => 'Martinez', 'lname' => 'Reyes', 'barangay_id' => 39, 'birthdate' => '1946-02-03', 'contact_number' => '09101234567', 'username' => 's123451234569'],
            ['id' => 68, 'osca_id' => '2345680', 'fname' => 'Mateo', 'mname' => 'Dela Cruz', 'lname' => 'Cruz', 'barangay_id' => 39, 'birthdate' => '1954-09-26', 'contact_number' => '09212345678', 'username' => 's123452345680'],
            ['id' => 69, 'osca_id' => '3456791', 'fname' => 'Isabel', 'mname' => 'Santos', 'lname' => 'Perez', 'barangay_id' => 40, 'birthdate' => '1948-06-14', 'contact_number' => '09323456789', 'username' => 's123453456791'],
            ['id' => 70, 'osca_id' => '4567802', 'fname' => 'Lorenzo', 'mname' => 'Gomez', 'lname' => 'Fernandez', 'barangay_id' => 40, 'birthdate' => '1950-11-30', 'contact_number' => '09434567890', 'username' => 's123454567802'],
            ['id' => 71, 'osca_id' => '5678913', 'fname' => 'Estela', 'mname' => 'Perez', 'lname' => 'Hernandez', 'barangay_id' => 41, 'birthdate' => '1947-04-23', 'contact_number' => '09545678901', 'username' => 's234565678913'],
            ['id' => 72, 'osca_id' => '6789024', 'fname' => 'Roberto', 'mname' => 'Villanueva', 'lname' => 'Garcia', 'barangay_id' => 41, 'birthdate' => '1952-01-10', 'contact_number' => '09656789012', 'username' => 's234566789024'],
            ['id' => 73, 'osca_id' => '7890135', 'fname' => 'Miguel', 'mname' => 'Santos', 'lname' => 'Lopez', 'barangay_id' => 42, 'birthdate' => '1949-10-01', 'contact_number' => '09767890123', 'username' => 's234567890135'],
            ['id' => 74, 'osca_id' => '8901246', 'fname' => 'Anita', 'mname' => 'Cruz', 'lname' => 'Rivera', 'barangay_id' => 42, 'birthdate' => '1953-07-08', 'contact_number' => '09878901234', 'username' => 's234568901246'],
            ['id' => 75, 'osca_id' => '9012357', 'fname' => 'Gloria', 'mname' => 'Reyes', 'lname' => 'Torres', 'barangay_id' => 43, 'birthdate' => '1945-09-19', 'contact_number' => '09989012345', 'username' => 's234569012357'],
            ['id' => 76, 'osca_id' => '1234570', 'fname' => 'Carlos', 'mname' => 'Hernandez', 'lname' => 'Sanchez', 'barangay_id' => 43, 'birthdate' => '1954-03-15', 'contact_number' => '09190123456', 'username' => 's234561234570'],
            ['id' => 77, 'osca_id' => '2345681', 'fname' => 'Felipe', 'mname' => 'Garcia', 'lname' => 'Martinez', 'barangay_id' => 44, 'birthdate' => '1946-01-02', 'contact_number' => '09201234567', 'username' => 's234562345681'],
            ['id' => 78, 'osca_id' => '3456792', 'fname' => 'Mariana', 'mname' => 'Lopez', 'lname' => 'Gomez', 'barangay_id' => 44, 'birthdate' => '1950-08-27', 'contact_number' => '09312345678', 'username' => 's234563456792'],
            ['id' => 79, 'osca_id' => '4567803', 'fname' => 'Santiago', 'mname' => 'Perez', 'lname' => 'Serrano', 'barangay_id' => 45, 'birthdate' => '1948-05-06', 'contact_number' => '09423456789', 'username' => 's234564567803'],
            ['id' => 80, 'osca_id' => '5678914', 'fname' => 'Lucia', 'mname' => 'Gonzalez', 'lname' => 'Torres', 'barangay_id' => 45, 'birthdate' => '1953-02-14', 'contact_number' => '09534567890', 'username' => 's234565678914'],
            ['id' => 81, 'osca_id' => '6789025', 'fname' => 'Patricia', 'mname' => 'Sanchez', 'lname' => 'Reyes', 'barangay_id' => 46, 'birthdate' => '1947-11-25', 'contact_number' => '09645678901', 'username' => 's345676789025'],
            ['id' => 82, 'osca_id' => '7890136', 'fname' => 'Diego', 'mname' => 'Gomez', 'lname' => 'Cruz', 'barangay_id' => 46, 'birthdate' => '1951-06-18', 'contact_number' => '09756789012', 'username' => 's345677890136'],
            ['id' => 83, 'osca_id' => '8901247', 'fname' => 'Celia', 'mname' => 'Torres', 'lname' => 'Lopez', 'barangay_id' => 47, 'birthdate' => '1949-03-03', 'contact_number' => '09867890123', 'username' => 's345678901247'],
            ['id' => 84, 'osca_id' => '9012358', 'fname' => 'Andres', 'mname' => 'Rivera', 'lname' => 'Garcia', 'barangay_id' => 47, 'birthdate' => '1955-01-22', 'contact_number' => '09978901234', 'username' => 's345679012358'],
            ['id' => 85, 'osca_id' => '1234571', 'fname' => 'Victoria', 'mname' => 'Santos', 'lname' => 'Hernandez', 'barangay_id' => 48, 'birthdate' => '1946-08-09', 'contact_number' => '09189012345', 'username' => 's345671234571'],
            ['id' => 86, 'osca_id' => '2345682', 'fname' => 'Joaquin', 'mname' => 'Martinez', 'lname' => 'Fernandez', 'barangay_id' => 48, 'birthdate' => '1952-04-05', 'contact_number' => '09290123456', 'username' => 's345672345682'],
            ['id' => 87, 'osca_id' => '3456793', 'fname' => 'Beatriz', 'mname' => 'Gonzalez', 'lname' => 'Sanchez', 'barangay_id' => 49, 'birthdate' => '1948-12-12', 'contact_number' => '09301234567', 'username' => 's345673456793'],
            ['id' => 88, 'osca_id' => '4567804', 'fname' => 'Javier', 'mname' => 'Lopez', 'lname' => 'Torres', 'barangay_id' => 49, 'birthdate' => '1953-09-29', 'contact_number' => '09412345678', 'username' => 's345674567804'],
            ['id' => 89, 'osca_id' => '5678915', 'fname' => 'Diana', 'mname' => 'Serrano', 'lname' => 'Cruz', 'barangay_id' => 50, 'birthdate' => '1945-11-14', 'contact_number' => '09523456789', 'username' => 's345675678915'],        
            ['id' => 90, 'osca_id' => '7890138', 'fname' => 'Alejandro', 'mname' => 'Gomez', 'lname' => 'Hernandez', 'barangay_id' => 30, 'birthdate' => '1949-08-19', 'contact_number' => '09623456789', 'username' => 's345676789138'],
            ['id' => 91, 'osca_id' => '7890137', 'fname' => 'Olivia', 'mname' => 'Garcia', 'lname' => 'Perez', 'barangay_id' => 26, 'birthdate' => '1947-07-07', 'contact_number' => '09745678901', 'username' => 's456787890137'],
            ['id' => 92, 'osca_id' => '8901248', 'fname' => 'Francisco', 'mname' => 'Sanchez', 'lname' => 'Martinez', 'barangay_id' => 26, 'birthdate' => '1954-04-04', 'contact_number' => '09856789012', 'username' => 's456788901248'],
            ['id' => 93, 'osca_id' => '9012359', 'fname' => 'Silvia', 'mname' => 'Gomez', 'lname' => 'Hernandez', 'barangay_id' => 27, 'birthdate' => '1949-01-01', 'contact_number' => '09967890123', 'username' => 's456789012359'],
            ['id' => 94, 'osca_id' => '2345683', 'fname' => 'Cecilia', 'mname' => 'Lopez', 'lname' => 'Garcia', 'barangay_id' => 27, 'birthdate' => '1954-06-22', 'contact_number' => '09178901234', 'username' => 's456782345683'],
            ['id' => 95, 'osca_id' => '3456794', 'fname' => 'Manuel', 'mname' => 'Martinez', 'lname' => 'Sanchez', 'barangay_id' => 28, 'birthdate' => '1948-09-15', 'contact_number' => '09289012345', 'username' => 's456783456794'],
            ['id' => 96, 'osca_id' => '4567805', 'fname' => 'Patricia', 'mname' => 'Gonzalez', 'lname' => 'Torres', 'barangay_id' => 28, 'birthdate' => '1951-12-28', 'contact_number' => '09390123456', 'username' => 's456784567805'],
            ['id' => 97, 'osca_id' => '5678916', 'fname' => 'Daniel', 'mname' => 'Serrano', 'lname' => 'Gomez', 'barangay_id' => 29, 'birthdate' => '1946-04-07', 'contact_number' => '09401234567', 'username' => 's456785678916'],
            ['id' => 98, 'osca_id' => '6789027', 'fname' => 'Veronica', 'mname' => 'Sanchez', 'lname' => 'Perez', 'barangay_id' => 29, 'birthdate' => '1952-03-03', 'contact_number' => '09512345678', 'username' => 's456786789027'],
            ['id' => 99, 'osca_id' => '7890138', 'fname' => 'Alejandro', 'mname' => 'Gomez', 'lname' => 'Hernandez', 'barangay_id' => 30, 'birthdate' => '1949-08-19', 'contact_number' => '09623456789', 'username' => 's456787890138'],
            ['id' => 100, 'osca_id' => '8901249', 'fname' => 'Ana', 'mname' => 'Martinez', 'lname' => 'Fernandez', 'barangay_id' => 30, 'birthdate' => '1953-05-14', 'contact_number' => '09734567890', 'username' => 's456788901249']
        ]);
        
    }
}
