<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // **Create Super Admin User**
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'super@gmail.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'is_active' => 1 // active
        ]);

        // **Create System Admin User**
        $systemAdmin = User::create([
            'name' => 'System Admin',
            'email' => 'system_admin@gmail.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'is_active' => 1 // active
        ]);

        $facultyAdmin = User::create([
            'name' => 'Faculty Admin',
            'email' => 'faculty_admin@gmail.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'is_active' => 1 // active
        ]);

        // **Create Roles**
        $superAdminRole = Role::where('name', 'Super Admin')->first();
        $systemAdminRole = Role::where('name', 'System Admin')->first();
        $facultyAdminRole = Role::where('name', 'Faculty Admin')->first();
        $userRole = Role::where('name', 'Member')->first();

        // **Assign Roles to Users**
        $superAdmin->assignRole($superAdminRole);
        $systemAdmin->assignRole($systemAdminRole);
        $facultyAdmin->assignRole($facultyAdminRole);

        $users =
            [
                [
                    'id' => 29,
                    'name' => 'دكتور /سعد بن علي الجلعود',
                    'email' => 'saad121@gmail.com',
                    'faculty_id' => 13,
                    'position_id' => 5,
                    'headquarter_id' => 7,
                ],
                [
                    'id' => 30,
                    'name' => 'دكتور / عبدالكريم بن ناصر  البرادى',
                    'email' => 'abdelkarim12@gmail.com',
                    'faculty_id' => 13,
                    'position_id' => 4,
                    'headquarter_id' => 7,
                ],
                [
                    'id' => 31,
                    'name' => 'دكتورة/ امل بنت احمد الريبش',
                    'email' => 'Aml1212@gmail.com',
                    'faculty_id' => 13,
                    'position_id' => 1,
                    'headquarter_id' => 7,
                ],
                [
                    'id' => 32,
                    'name' => 'دكتور / إبراهيم بن سليمان اللاحم',
                    'email' => 'doctorebrahim22@gmail.com',
                    'faculty_id' => 10,
                    'position_id' => 5,
                    'headquarter_id' => 6,
                ],
                [
                    'id' => 33,
                    'name' => 'دكتور / سلطان بن سليمان العضيبى',
                    'email' => 'drsultan@gmail.com',
                    'faculty_id' => 10,
                    'position_id' => 4,
                    'headquarter_id' => 6,
                ],
                [
                    'id' => 34,
                    'name' => 'دكتور / فهد سليمان نزال الحربى',
                    'email' => 'fahd22@gmail.com',
                    'faculty_id' => 10,
                    'position_id' => 1,
                    'headquarter_id' => 6,
                ],
                [
                    'id' => 35,
                    'name' => 'دكتور /د/ عبدالمجيد بن عبدالله القسومي',
                    'email' => 'drabdemeged21@gmail.com',
                    'faculty_id' => 12,
                    'position_id' => 5,
                    'headquarter_id' => 7,
                ],
                [
                    'id' => 36,
                    'name' => 'دكتور / ياسر بن صالح المقبل',
                    'email' => 'dryasser22@gmail.com',
                    'faculty_id' => 12,
                    'position_id' => 4,
                    'headquarter_id' => 7,
                ],
                [
                    'id' => 37,
                    'name' => 'دكتور / وليد بن محمد الطويان',
                    'email' => 'walid@gmail.com',
                    'faculty_id' => 12,
                    'position_id' => 1,
                    'headquarter_id' => 7,
                ],
                [
                    'id' => 38,
                    'name' => 'د/ عائشة بنت ناصر البطاح',
                    'email' => 'Aisha@gmail.com',
                    'faculty_id' => 11,
                    'position_id' => 5,
                    'headquarter_id' => 6,
                ],
                [
                    'id' => 39,
                    'name' => 'أستاذة /هيفاء بنت سليمان الحربي',
                    'email' => 'haifaa@gmail.com',
                    'faculty_id' => 11,
                    'position_id' => 4,
                    'headquarter_id' => 6,
                ],
                [
                    'id' => 40,
                    'name' => 'دكتورة/ مها صلاح الدين محمد',
                    'email' => 'maha55@gmail.com',
                    'faculty_id' => 11,
                    'position_id' => 1,
                    'headquarter_id' => 6,
                ],
                [
                    'id' => 41,
                    'name' => 'أ.د. علي بن عمر السحيباني',
                    'email' => 'dra0545@gmail.com',
                    'faculty_id' => 10,
                    'position_id' => 3,
                    'headquarter_id' => 6,
                ],
                [
                    'id' => 42,
                    'name' => 'أ.د. محمد بن حمد المحيميد',
                    'email' => 'm-mohammeed@hotmail.com',
                    'faculty_id' => 10,
                    'position_id' => 2,
                    'headquarter_id' => 6,
                ],
                [
                    'id' => 43,
                    'name' => 'أ.د. أحمد بن محمد البريدي',
                    'email' => 'am2121@gmail.com',
                    'faculty_id' => 10,
                    'position_id' => 1,
                    'headquarter_id' => 6,
                ],
                [
                    'id' => 44,
                    'name' => 'أ.د / تركي بن فهد الغميز',
                    'email' => 'Torki2008@gmail.com',
                    'faculty_id' => 10,
                    'position_id' => 3,
                    'headquarter_id' => 6,
                ],
                [
                    'id' => 45,
                    'name' => 'أ.د أحمد بن عبدالله الدغيري-رئيس القسم -الاستشعار في الجيومورفولوجيا',
                    'email' => 'adgierie@qu.edu.sa',
                    'faculty_id' => 11,
                    'position_id' => 3,
                    'headquarter_id' => 6,
                ],
                [
                    'id' => 46,
                    'name' => 'أ.د عبدالله عبدالرحمن المسند -متقاعد- جغرافية المناخ',
                    'email' => 'aamsnd@qu.edu.sa',
                    'faculty_id' => 11,
                    'position_id' => 2,
                    'headquarter_id' => 6,
                ],
                [
                    'id' => 47,
                    'name' => 'أ.د حسين أحمد المحمد -النظم في المناخ',
                    'email' => 'hussien@gmail.com',
                    'faculty_id' => 11,
                    'position_id' => 1,
                    'headquarter_id' => 6,
                ],
                [
                    'id' => 48,
                    'name' => 'أ.د /الطيب الامين محمد عيد',
                    'email' => 'eem.eid@qu.edu.sa',
                    'faculty_id' => 12,
                    'position_id' => 3,
                    'headquarter_id' => 7,
                ],
                [
                    'id' => 49,
                    'name' => 'أ.د /حبيب الله خليل الله عبد العزيز',
                    'email' => 'h.abdulaziz@qu.edu.sa',
                    'faculty_id' => 12,
                    'position_id' => 2,
                    'headquarter_id' => 7,
                ],
                [
                    'id' => 50,
                    'name' => 'أ / نفين عبد اللطيف  بن محمد',
                    'email' => 'n.ghazali@qu.edu.sa',
                    'faculty_id' => 12,
                    'position_id' => 1,
                    'headquarter_id' => 7,
                ],
                [
                    'id' => 51,
                    'name' => 'أ.د/ اسماعيل عبدالرحمن علي السحيباني - الدراسات اللغوية',
                    'email' => 'suhaibani@qu.edu.sa',
                    'faculty_id' => 13,
                    'position_id' => 3,
                    'headquarter_id' => 7,
                ],
                [
                    'id' => 52,
                    'name' => 'أ.د /احمد محمد عبدالرحمن حسانين - الدراسات اللغوية',
                    'email' => 'amhsanien@qu.edu.sa',
                    'faculty_id' => 13,
                    'position_id' => 2,
                    'headquarter_id' => 7,
                ],
                [
                    'id' => 53,
                    'name' => 'أ / خالد صالح حمد الشبل - الدراسات اللغوية',
                    'email' => 'kshbl@qu.edu.sa',
                    'faculty_id' => 13,
                    'position_id' => 1,
                    'headquarter_id' => 7,
                ],
            ];

        foreach ($users as $userData) {
            $user = User::create([
                'id' => $userData['id'],
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'headquarter_id' => $userData['headquarter_id'],
                'position_id' => $userData['position_id'],
                'faculty_id' => $userData['faculty_id'],
                'is_active' => 1, // let users active
                'email_verified_at' => NULL,
                'remember_token' => NULL,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $user->assignRole($userRole);  // Assign role to the created user
        }
    }
}
