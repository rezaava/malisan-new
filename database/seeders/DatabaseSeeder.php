<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\StudentProfile;
use App\Models\VendorProfile;
use Illuminate\Database\Seeder;
use App\Models\Tag;
use App\Models\TeacherProfile;
use App\Models\Grade;
use App\Models\Role;
use App\Models\Product;
use App\Models\ProductChapter;
use App\Models\User;
use App\Models\Lesson;

class DatabaseSeeder extends Seeder
{
    /*
    |--------------------------------------------------------------------------
    | Helper
    |--------------------------------------------------------------------------
    */

    protected function createGrade(string $name)
    {
        $grade = new Grade();
        $grade->name = $name;
        $grade->photo = 'img/pro.jpeg';
        $grade->save();

        return $grade;
    }

    protected function createRole(string $name, string $display_name)
    {
        $role = new Role();
        $role->name = $name;
        $role->display_name = $display_name;
        $role->save();

        return $role;
    }

    protected function createLessonTag(string $name)
    {
        $tag = new Tag();
        $tag->name = $name;
        $tag->save();

        return $tag;
    }

    protected function createChaptersForProduct(Product $product, array $chaptersData)
    {
        foreach ($chaptersData as $order => $chapterData) {

            $chapter = ProductChapter::create([
                'product_id' => $product->id,
                'title' => $chapterData['title'],
                'parent_id' => null,
                'order' => $order + 1,
            ]);

            if (!empty($chapterData['children'])) {
                foreach ($chapterData['children'] as $childOrder => $childTitle) {

                    ProductChapter::create([
                        'product_id' => $product->id,
                        'title' => $childTitle,
                        'parent_id' => $chapter->id,
                        'order' => $childOrder + 1,
                    ]);

                }
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Vendor
    |--------------------------------------------------------------------------
    */

    protected function seedVendor()
    {
        $user = new User();
        $user->first_name = 'رضا';
        $user->last_name = 'فروشنده';
        $user->mobile = '09121111111';
        $user->photo = 'file/img/user/pro.jpeg';
        $user->password = bcrypt('123456');
        $user->save();

        $user->addRole('vendor');

        $vendorProfile = new VendorProfile();
        $vendorProfile->user_id = $user->id;
        $vendorProfile->national_code = '9876543210';
        $vendorProfile->iban = 'IR987654321098765432109876';
        $vendorProfile->commission_percent = 10;
        $vendorProfile->save();

        return $user;
    }

    /*
    |--------------------------------------------------------------------------
    | Teacher
    |--------------------------------------------------------------------------
    */

    protected function seedTeacher($vendor)
    {
        $user = new User();
        $user->first_name = 'علی';
        $user->last_name = 'معلمی';
        $user->mobile = '09120000000';
        $user->photo = 'file/img/user/pro.jpeg';
        $user->password = bcrypt('123456');
        $user->save();

        $user->addRole('teacher');

        $teacherProfile = new TeacherProfile();
        $teacherProfile->user_id = $user->id;
        $teacherProfile->status = 'published';
        $teacherProfile->vendor_id = $vendor->id; // ✅ اتصال به فروشنده
        $teacherProfile->national_code = '1234567890';
        $teacherProfile->birth_date = '1990-01-01';
        $teacherProfile->teaching_experience = 5;
        $teacherProfile->iban = 'IR123456789012345678901234';
        $teacherProfile->commission_percent = 20;
        $teacherProfile->gender = 'male';
        $teacherProfile->bio = 'معلم تستی برای داده‌های اولیه سیستم.';
        $teacherProfile->save();

        return $teacherProfile;
    }

    protected function seedAdmin($name, $family, $number)
    {
        $user = new User();
        $user->first_name = $name;
        $user->last_name = $family;
        $user->mobile = $number;
        $user->photo = 'file/img/user/pro.jpeg';
        $user->password = bcrypt('123456');
        $user->save();

        $user->addRole('admin');
    }

    /*
    |--------------------------------------------------------------------------
    | Product
    |--------------------------------------------------------------------------
    */

    protected function createProduct($vendor, array $data)
    {
        $product = new Product();

        $product->title = $data['title'];
        $product->type = $data['type'];
        $product->description = $data['description'] ?? null;
        $product->price = $data['price'];
        $product->off = $data['off'] ?? 0;
        $product->photo = $data['photo'];

        $product->teacher_id = $data['teacher_id'];
        $product->vendor_id = $vendor->id; // ✅ vendor

        $product->grade_id = $data['grade_id'];
        $product->lesson_id = $data['lesson_id'];

        $product->video_url = $data['video_url'] ?? null;
        $product->file_url = $data['file_url'] ?? null;
        $product->duration = $data['duration'] ?? null;

        $product->video_duration = $data['video_duration'] ?? null;
        $product->page_count = $data['page_count'] ?? null;
        $product->file_size = $data['file_size'] ?? 0;

        $product->status = $data['status'] ?? 'published';

        $product->save();

        if (!empty($data['tag_ids']) && method_exists($product, 'tags')) {
            $product->tags()->sync($data['tag_ids']);
        }

        return $product;
    }

    /*
    |--------------------------------------------------------------------------
    | Students
    |--------------------------------------------------------------------------
    */

    protected function seedStudents($count = 20)
    {
        $students = [];

        for ($i = 1; $i <= $count; $i++) {

            $user = new User();
            $user->first_name = "{$i} علی";
            $user->last_name = "ممدی{$i}";
            $user->mobile = "09120000" . str_pad($i, 3, '0', STR_PAD_LEFT);
            $user->photo = "file/img/user/pro.jpeg";
            $user->password = bcrypt('123456');
            $user->save();

            $user->addRole('student');

            $studentProfile = new StudentProfile();
            $studentProfile->user_id = $user->id;
            $studentProfile->save();

            $students[] = $user;
        }

        return $students;
    }

    /*
    |--------------------------------------------------------------------------
    | Run Seeder
    |--------------------------------------------------------------------------
    */

    protected function seedCommentsForProduct(Product $product, $students, $count = 10)
    {
        $comments = [
            'خیلی آموزش خوبی بود ممنون از استاد',
            'توضیحات کامل و قابل فهم بود',
            'مثال‌ها خیلی کمک کرد مطلب را بفهمم',
            'ای کاش تمرین بیشتری هم داشت',
            'کیفیت تدریس عالی بود',
            'مبحث سختی بود ولی خوب توضیح داده شد',
            'برای امتحان خیلی کمکم کرد',
            'ممنون از آموزش خوبتون',
            'نکات تستی خیلی مفید بود',
            'یکی از بهترین آموزش‌هایی بود که دیدم',
            'اگر تمرین بیشتری داشت بهتر میشد',
            'خیلی روان و قابل فهم توضیح داده شد',
        ];

        for ($i = 0; $i < $count; $i++) {

            $comment = new Comment();

            $comment->user_id = $students[array_rand($students)]->id;
            $comment->product_id = $product->id;
            $comment->text = $comments[array_rand($comments)];
            $comment->status = 'published';

            $comment->save();
        }
    }

    /*
    |--------------------------------------------------------------------------
    | اجرای اصلی Seeder
    |--------------------------------------------------------------------------
    */

    public function run()
    {
        // 1) نقش‌ها
        $this->createRole('admin', 'admin');
        $this->createRole('student', 'student');
        $this->createRole('teacher', 'teacher');
        $this->createRole('vendor', 'vendor');

        $this->seedAdmin('علی رضا', 'شیرزاد', '09134609794');
        $this->seedAdmin('Arshia', 'JA', '09135578416');


    }
}
