<?php

namespace Database\Seeders;

use App\Models\About;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AboutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        About::create([
            'name' => 'Library Administrator',
            'bio' => 'Welcome to our Online Library! We are dedicated to providing access to a wide collection of books and literary resources. Our mission is to promote reading and learning in our community.

This library was established with the goal of making knowledge accessible to everyone. We believe that books have the power to transform lives and open new worlds of imagination and understanding.

Feel free to browse our collection and discover your next great read!',
            'profile_image' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400',
            'contact_info' => 'Email: library@example.com
Phone: +1 (555) 123-4567
Address: 123 Library Street, Book City, BC 12345

Opening Hours:
Monday - Friday: 9:00 AM - 6:00 PM
Saturday: 10:00 AM - 4:00 PM
Sunday: Closed',
        ]);
    }
}
