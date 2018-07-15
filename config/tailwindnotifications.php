<?php

return [
    'bags' => [
        'success' => [
            'plural' => 'successes',
            'html' => [
                'bag' => [
                    'start' => '
<div class="bg-green-lightest border-t border-b border-green text-green-dark px-4 py-3 mb-2" role="alert">
    <p class="font-bold">Success !</p>',
                    'end' => '
</div>',
                ],
                'notification' => [
                    'start' => '
<p class="text-sm">',
                    'end' => '
</p>',
                ],
            ],
        ],
        'error' => [
            'html' => [
                'bag' => [
                    'start' => '
<div class="bg-red-lightest border-t border-b border-red text-red-dark px-4 py-3 mb-2" role="alert">
    <p class="font-bold">Error !</p>',
                    'end' => '
</div>',
                ],
                'notification' => [
                    'start' => '
<p class="text-sm">',
                    'end' => '
</p>',
                ],
            ],
        ],
        'warning' => [
            'html' => [
                'bag' => [
                    'start' => '
<div class="bg-orange-lightest border-t border-b border-orange text-orange-dark px-4 py-3 mb-2" role="alert">
    <p class="font-bold">Warning !</p>',
                    'end' => '
</div>',
                ],
                'notification' => [
                    'start' => '
<p class="text-sm">',
                    'end' => '
</p>',
                ],
            ],
        ],
        'message' => [
            'html' => [
                'bag' => [
                    'start' => '
<div class="bg-blue-lightest border-t border-b border-blue text-blue-dark px-4 py-3 mb-2" role="alert">
    <p class="font-bold">Information !</p>',
                    'end' => '
</div>',
                ],
                'notification' => [
                    'start' => '
<p class="text-sm">',
                    'end' => '
</p>',
                ],
            ],
        ],
    ],
    'themes' => [
        'demo' => [
            'success' => [
                'bag' => [
                    'start' => '
<div class="bg-green-lightest border-t border-b border-green text-green-dark px-4 py-3 mb-2" role="alert">
<p class="font-bold">Success !</p>',
                    'end' => '
</div>',
                ],
                'notification' => [
                    'start' => '
<p class="text-sm">',
                    'end' => '
</p>',
                ],
            ],
            'error' => [
                'notification' => [
                    'start' => '
<div role="alert" class="mb-2">
<div class="bg-red text-white font-bold rounded-t px-4 py-2">
    Danger
</div>
<div class="border border-t-0 border-red-light rounded-b bg-red-lightest px-4 py-3 text-red-dark">
    <p>',
                    'end' => '
    </p>
</div>
</div>',
                ],
            ],
            'warning' => [
                'bag' => [
                    'start' => '
<div class="bg-orange-lightest border-l-4 border-orange text-orange-dark p-4 mb-2" role="alert">
<p class="font-bold">Be Warned</p>',
                    'end' => '
</div>',
                ],
                'notification' => [
                    'start' => '
<p>',
                    'end' => '
</p>',
                ],
            ],
            'information' => [
                'notification' => [
                    'start' => '
<div class="flex items-center bg-blue text-white text-sm font-bold px-4 py-3 mb-2" role="alert">
<svg class="fill-current w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M12.432 0c1.34 0 2.01.912 2.01 1.957 0 1.305-1.164 2.512-2.679 2.512-1.269 0-2.009-.75-1.974-1.99C9.789 1.436 10.67 0 12.432 0zM8.309 20c-1.058 0-1.833-.652-1.093-3.524l1.214-5.092c.211-.814.246-1.141 0-1.141-.317 0-1.689.562-2.502 1.117l-.528-.88c2.572-2.186 5.531-3.467 6.801-3.467 1.057 0 1.233 1.273.705 3.23l-1.391 5.352c-.246.945-.141 1.271.106 1.271.317 0 1.357-.392 2.379-1.207l.6.814C12.098 19.02 9.365 20 8.309 20z"/></svg>
<p>',
                    'end' => '
</p>
</div>',
                ],
            ],
        ],
    ],
];