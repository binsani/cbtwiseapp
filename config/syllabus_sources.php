<?php

return [
    'utme' => [
        'label' => 'Official JAMB Integrated Brochure and Syllabus System (IBASS)',
        'url' => 'https://ibass.jamb.gov.ng/',
        'status' => 'official',
        'note' => 'Topic headings are maintained against the official UTME syllabus.',
    ],
    'waec' => [
        'label' => 'WAEC e-Learning and NERDC Senior Secondary Curriculum',
        'url' => 'https://www.waeconline.org.ng/e-learning/',
        'status' => 'official',
        'note' => 'WAEC-approved subject guidance is cross-checked against the national senior-secondary curriculum.',
    ],
    'neco' => [
        'label' => 'NERDC Senior Secondary Curriculum',
        'url' => 'https://www.nerdc.gov.ng/content_manager/new_senior_curriculum_home.html',
        'status' => 'aligned',
        'note' => 'NECO sells its detailed syllabus. This catalogue follows the official national curriculum until a licensed NECO syllabus copy is added.',
    ],
    'post-utme' => [
        'label' => 'Institution-specific Post-UTME requirements',
        'url' => null,
        'status' => 'institution_specific',
        'note' => 'Post-UTME content varies by institution; this practice area uses the matching senior-secondary subject foundation.',
    ],
];
