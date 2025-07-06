<?php

// MTGemini/config/vibe.php

return [
    'definitions' => [
        'beach_getaway' => [
            'style' => ['casual', 'bohemian'],
            'material' => ['cotton', 'linen'],
            'color_tone' => ['bright', 'pastel'],
            'occasion' => ['beach', 'holiday'],
            'sleeve_length' => ['short_sleeve', 'sleeveless']
        ],
        'elegant_evening' => [
            'style' => ['elegant', 'minimalist', 'glamour'],
            'material' => ['silk', 'polyester', 'velvet'],
            'color_tone' => ['dark', 'monochrome', 'jewel'],
            'occasion' => ['party', 'formal'],
            'neckline' => ['v_neck', 'turtleneck']
        ],
        'sporty_active' => [
            'style' => ['sporty'],
            'material' => ['cotton', 'polyester'],
            'color_tone' => ['bright', 'monochrome'],
            'general_tags' => ['water_resistant']
        ],
        'daily_casual' => [
            'style' => ['casual', 'everyday'],
            'material' => ['cotton', 'denim'],
            'color_tone' => ['earthy', 'pastel'],
            'occasion' => ['loungewear', 'casual_day', 'office']
        ],
        // Add more vibe definitions here as needed
        // Ensure keys (e.g., 'style', 'material') match 'vibe_attributes' in your JSON metadata
    ],
];