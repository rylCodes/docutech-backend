<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Support\Str;
use App\Models\Documentation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Documentation>
 */
class DocumentationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $content = [
            [
                'type' => 'Header',
                'text' => fake()->sentence(3),
            ],
            [
                'type' => 'Paragraph',
                'text' => fake()->paragraph(5),
            ],
            [
                'type' => 'Header',
                'text' => fake()->sentence(3),
            ],
            [
                'type' => 'Paragraph',
                'text' => fake()->paragraph(5),
            ],
            [
                'type' => 'Image',
                // 'url' => 'https://source.unsplash.com/random/?tech&' . fake()->numberBetween(1, 10),
                'url' => 'https://images.unsplash.com/photo-1488590528505-98d2b5aba04b?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                'caption' => fake()->sentence()
            ],
            [
                'type' => 'Paragraph',
                'text' => fake()->paragraph(5),
            ],
            [
                'type' => 'Code Block',
                'language' => 'javascript',
                'code' => "  // Content Form Handler\nfunction handleAddToContent() {\n  if (selectedType === 'Header') {\n    addNewHeader();\n   }  else if (selectedType === 'Paragraph') {\n    addNewParagraph();\n  } else if (selectedType === 'Code Block') {\n    addNewCodeBlock();\n  } else {\n    return;\n  }\n  console.log(content);\n}",
            ],
            [
                'type' => 'Paragraph',
                'text' => fake()->paragraph(5),
            ],
            [
                'type' => 'Header',
                'text' => fake()->sentence(3),
            ],
            [
                'type' => 'Paragraph',
                'text' => fake()->paragraph(5),
            ],
            [
                'type' => 'Code Block',
                'language' => 'python',
                'code' => "class CustomDataStructure:\n    def __init__(self):\n        self.data = []\n    def add_element(self, element):\n        self.data.append(element)\n    def remove_element(self, element):\n        self.data.remove(element)",
            ],
            [
                'type' => 'Header',
                'text' => fake()->sentence(3),
            ],
            [
                'type' => 'Paragraph',
                'text' => fake()->paragraph(5),
            ],
            [
                'type' => 'List',
                'listType' => "plain",
                'items' => [
                    'Step 1: Choose the right data structure',
                    'Step 2: Implement custom data structures if needed',
                    'Step 3: Optimize performance',
                ],
            ],
            [
                'type' => 'Header',
                'text' => fake()->sentence(3),
            ],
            [
                'type' => 'Paragraph',
                'text' => fake()->paragraph(5),
            ],
            [
                'type' => 'Code Block',
                'language' => 'python',
                'code' => "class CustomDataStructure:\n    def __init__(self):\n        self.data = []\n    def add_element(self, element):\n        self.data.append(element)\n    def remove_element(self, element):\n        self.data.remove(element)",
            ],
            [
                'type' => 'Header',
                'text' => fake()->sentence(3),
            ],
            [
                'type' => 'Paragraph',
                'text' => fake()->paragraph(5),
            ],
            [
                'type' => 'Code Block',
                'language' => 'python',
                'code' => "class CustomDataStructure:\n    def __init__(self):\n        self.data = []\n    def add_element(self, element):\n        self.data.append(element)\n    def remove_element(self, element):\n        self.data.remove(element)",
            ],
            [
                'type' => 'Link',
                'url' => fake()->url(),
                'text' => fake()->sentence(3),
            ],
        ];

        $title = fake()->sentence(3);

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'author' => fake()->name(),
            'icon' => 'FaLaptopCode',
            'description' => fake()->paragraph(),
            'tags' => fake()->words(3, true),
            'content' => $content,
            'user_id' => User::factory()
        ];
    }
}
