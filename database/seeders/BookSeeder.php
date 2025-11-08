<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $books = [
            [
                'title' => 'The Great Gatsby',
                'author' => 'F. Scott Fitzgerald',
                'description' => 'A classic American novel set in the Jazz Age, following the mysterious millionaire Jay Gatsby and his obsession with Daisy Buchanan.',
                'cover_url' => 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?w=400',
            ],
            [
                'title' => 'To Kill a Mockingbird',
                'author' => 'Harper Lee',
                'description' => 'A gripping tale of racial injustice and childhood innocence in the American South, told through the eyes of Scout Finch.',
                'cover_url' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400',
            ],
            [
                'title' => '1984',
                'author' => 'George Orwell',
                'description' => 'A dystopian novel about totalitarian surveillance and thought control, following Winston Smith in a society ruled by Big Brother.',
                'cover_url' => 'https://images.unsplash.com/photo-1481627834876-b7833e8f5570?w=400',
            ],
            [
                'title' => 'Pride and Prejudice',
                'author' => 'Jane Austen',
                'description' => 'A romantic novel of manners that follows the character development of Elizabeth Bennet, who learns about the repercussions of hasty judgments.',
                'cover_url' => 'https://images.unsplash.com/photo-1512820790803-83ca750da615?w=400',
            ],
            [
                'title' => 'The Catcher in the Rye',
                'author' => 'J.D. Salinger',
                'description' => 'A controversial coming-of-age story about Holden Caulfield, a teenager struggling with alienation and loss of innocence.',
                'cover_url' => 'https://images.unsplash.com/photo-1516979187457-637abb4f9353?w=400',
            ],
        ];

        foreach ($books as $book) {
            Book::create($book);
        }
    }
}
