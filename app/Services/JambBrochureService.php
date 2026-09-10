<?php

namespace App\Services;

class JambBrochureService
{
    /**
     * Get all faculties with their courses and UTME requirements.
     */
    public static function getFaculties(): array
    {
        return [
            'medical' => [
                'name' => 'Medical, Pharmaceutical & Health Sciences',
                'icon' => '🩺',
                'description' => 'Medicine, Surgery, Pharmacy, Nursing, Medical Lab Science, Dentistry and allied health fields.',
                'courses' => [
                    [
                        'name' => 'Medicine and Surgery (MBBS)',
                        'slug' => 'medicine-and-surgery',
                        'utme_subjects' => ['English Language', 'Biology', 'Chemistry', 'Physics'],
                        'utme_slugs' => ['english-language', 'biology', 'chemistry', 'physics'],
                        'olevel_requirements' => '5 SSCE credit passes in English Language, Mathematics, Physics, Chemistry, and Biology in not more than one sitting.',
                        'remarks' => 'Highly competitive. Aim for 280+ in JAMB UTME.',
                        'institutions' => ['UNILAG', 'UI', 'ABU', 'UNN', 'OAU', 'UNILORIN', 'UNIBEN', 'BUK'],
                    ],
                    [
                        'name' => 'Pharmacy',
                        'slug' => 'pharmacy',
                        'utme_subjects' => ['English Language', 'Biology', 'Chemistry', 'Physics'],
                        'utme_slugs' => ['english-language', 'biology', 'chemistry', 'physics'],
                        'olevel_requirements' => '5 SSCE credit passes in English Language, Mathematics, Physics, Chemistry, and Biology.',
                        'remarks' => 'Strong proficiency in Organic Chemistry and Biology required.',
                        'institutions' => ['OAU', 'UNILAG', 'UI', 'UNN', 'ABU', 'UNIBEN', 'UNIMAID'],
                    ],
                    [
                        'name' => 'Nursing / Nursing Science',
                        'slug' => 'nursing-science',
                        'utme_subjects' => ['English Language', 'Biology', 'Chemistry', 'Physics'],
                        'utme_slugs' => ['english-language', 'biology', 'chemistry', 'physics'],
                        'olevel_requirements' => '5 SSCE credit passes in English Language, Mathematics, Physics, Chemistry, and Biology in not more than 2 sittings.',
                        'remarks' => 'One of the most competitive healthcare courses in Nigeria.',
                        'institutions' => ['UI', 'UNILAG', 'UNN', 'UNILORIN', 'ABU', 'UNICAL', 'FUTO'],
                    ],
                    [
                        'name' => 'Medical Laboratory Science',
                        'slug' => 'medical-laboratory-science',
                        'utme_subjects' => ['English Language', 'Biology', 'Chemistry', 'Physics'],
                        'utme_slugs' => ['english-language', 'biology', 'chemistry', 'physics'],
                        'olevel_requirements' => '5 SSCE credit passes in English Language, Mathematics, Chemistry, Biology, and Physics.',
                        'remarks' => 'Laboratory diagnostics and biomedical analysis.',
                        'institutions' => ['UNIBEN', 'UNN', 'UNILORIN', 'UDUSOK', 'UNICAL', 'AAU'],
                    ],
                    [
                        'name' => 'Dentistry / Dental Surgery',
                        'slug' => 'dentistry',
                        'utme_subjects' => ['English Language', 'Biology', 'Chemistry', 'Physics'],
                        'utme_slugs' => ['english-language', 'biology', 'chemistry', 'physics'],
                        'olevel_requirements' => '5 SSCE credit passes in English, Mathematics, Biology, Chemistry, and Physics.',
                        'remarks' => 'Oral medicine, oral surgery and maxillofacial therapy.',
                        'institutions' => ['UI', 'UNILAG', 'UNIBEN', 'OAU', 'UNN', 'LASU'],
                    ],
                    [
                        'name' => 'Physiotherapy',
                        'slug' => 'physiotherapy',
                        'utme_subjects' => ['English Language', 'Biology', 'Chemistry', 'Physics'],
                        'utme_slugs' => ['english-language', 'biology', 'chemistry', 'physics'],
                        'olevel_requirements' => '5 SSCE credit passes in English, Mathematics, Physics, Chemistry, and Biology.',
                        'remarks' => 'Physical rehabilitation and neurological therapy.',
                        'institutions' => ['UI', 'UNILAG', 'UNN', 'BUK', 'UNIMAID'],
                    ],
                    [
                        'name' => 'Anatomy / Physiology',
                        'slug' => 'anatomy-physiology',
                        'utme_subjects' => ['English Language', 'Biology', 'Chemistry', 'Physics'],
                        'utme_slugs' => ['english-language', 'biology', 'chemistry', 'physics'],
                        'olevel_requirements' => '5 SSCE credit passes in English, Mathematics, Biology, Chemistry, and Physics.',
                        'remarks' => 'Basic medical sciences; great foundation for graduate medicine.',
                        'institutions' => ['UNILAG', 'UI', 'OAU', 'UNILORIN', 'ABU', 'EKSU', 'DELSU'],
                    ],
                ],
            ],

            'engineering' => [
                'name' => 'Engineering & Technology',
                'icon' => '⚙️',
                'description' => 'Mechanical, Electrical, Civil, Chemical, Computer, Petroleum and Mechatronics Engineering.',
                'courses' => [
                    [
                        'name' => 'Mechanical Engineering',
                        'slug' => 'mechanical-engineering',
                        'utme_subjects' => ['English Language', 'Mathematics', 'Physics', 'Chemistry'],
                        'utme_slugs' => ['english-language', 'mathematics', 'physics', 'chemistry'],
                        'olevel_requirements' => '5 SSCE credit passes in English Language, Mathematics, Physics, Chemistry, and one other science subject.',
                        'remarks' => 'Thermodynamics, robotics, materials science and mechanical design.',
                        'institutions' => ['FUTO', 'UNILAG', 'ABU', 'OAU', 'UNN', 'FUTA', 'UNILORIN', 'UNIBEN'],
                    ],
                    [
                        'name' => 'Electrical / Electronics Engineering',
                        'slug' => 'electrical-electronics-engineering',
                        'utme_subjects' => ['English Language', 'Mathematics', 'Physics', 'Chemistry'],
                        'utme_slugs' => ['english-language', 'mathematics', 'physics', 'chemistry'],
                        'olevel_requirements' => '5 SSCE credit passes in English Language, Mathematics, Physics, Chemistry, and any other relevant science.',
                        'remarks' => 'Power systems, telecommunications, embedded hardware and electronics.',
                        'institutions' => ['UNILAG', 'OAU', 'UNN', 'ABU', 'FUTA', 'UNILORIN', 'FUTO'],
                    ],
                    [
                        'name' => 'Civil Engineering',
                        'slug' => 'civil-engineering',
                        'utme_subjects' => ['English Language', 'Mathematics', 'Physics', 'Chemistry'],
                        'utme_slugs' => ['english-language', 'mathematics', 'physics', 'chemistry'],
                        'olevel_requirements' => '5 SSCE credit passes in English Language, Mathematics, Physics, Chemistry, and one other science.',
                        'remarks' => 'Structural engineering, water resources, transportation, and foundations.',
                        'institutions' => ['ABU', 'UNILAG', 'OAU', 'UNN', 'UNILORIN', 'FUTA', 'UNIBEN'],
                    ],
                    [
                        'name' => 'Computer Engineering',
                        'slug' => 'computer-engineering',
                        'utme_subjects' => ['English Language', 'Mathematics', 'Physics', 'Chemistry'],
                        'utme_slugs' => ['english-language', 'mathematics', 'physics', 'chemistry'],
                        'olevel_requirements' => '5 SSCE credit passes in English Language, Mathematics, Physics, Chemistry, and one science subject.',
                        'remarks' => 'Hardware engineering, computer architecture, IoT and embedded systems.',
                        'institutions' => ['UNILAG', 'FUTA', 'OAU', 'ABU', 'UNN', 'FUTO', 'LAUTECH'],
                    ],
                    [
                        'name' => 'Chemical Engineering',
                        'slug' => 'chemical-engineering',
                        'utme_subjects' => ['English Language', 'Mathematics', 'Physics', 'Chemistry'],
                        'utme_slugs' => ['english-language', 'mathematics', 'physics', 'chemistry'],
                        'olevel_requirements' => '5 SSCE credit passes in English Language, Mathematics, Physics, Chemistry, and Biology/Agric.',
                        'remarks' => 'Petrochemicals, industrial synthesis, thermodynamics and polymers.',
                        'institutions' => ['UNILAG', 'OAU', 'ABU', 'UNN', 'FUTO', 'UNIBEN'],
                    ],
                    [
                        'name' => 'Petroleum & Gas Engineering',
                        'slug' => 'petroleum-engineering',
                        'utme_subjects' => ['English Language', 'Mathematics', 'Physics', 'Chemistry'],
                        'utme_slugs' => ['english-language', 'mathematics', 'physics', 'chemistry'],
                        'olevel_requirements' => '5 SSCE credit passes in English Language, Mathematics, Physics, Chemistry, and any science.',
                        'remarks' => 'Drilling, reservoir engineering and energy resources.',
                        'institutions' => ['UNILAG', 'UI', 'UNIPORT', 'FUTO', 'UNIBEN', 'ATBU'],
                    ],
                ],
            ],

            'sciences' => [
                'name' => 'Natural & Physical Sciences / Computing',
                'icon' => '💻',
                'description' => 'Computer Science, Software Engineering, Microbiology, Biochemistry, Industrial Chemistry and Physics.',
                'courses' => [
                    [
                        'name' => 'Computer Science / Information Technology',
                        'slug' => 'computer-science',
                        'utme_subjects' => ['English Language', 'Mathematics', 'Physics', 'Chemistry'],
                        'utme_slugs' => ['english-language', 'mathematics', 'physics', 'chemistry'],
                        'olevel_requirements' => '5 SSCE credit passes in English Language, Mathematics, Physics, plus two of Chemistry, Biology, Economics or Further Maths.',
                        'remarks' => 'Some universities accept Biology or Economics in place of Chemistry.',
                        'institutions' => ['UNILAG', 'UI', 'OAU', 'FUTA', 'UNN', 'ABU', 'UNILORIN', 'KWASU'],
                    ],
                    [
                        'name' => 'Biochemistry',
                        'slug' => 'biochemistry',
                        'utme_subjects' => ['English Language', 'Biology', 'Chemistry', 'Physics'],
                        'utme_slugs' => ['english-language', 'biology', 'chemistry', 'physics'],
                        'olevel_requirements' => '5 SSCE credit passes in English Language, Mathematics, Physics, Chemistry, and Biology.',
                        'remarks' => 'Molecular biology, enzymatic kinetics and clinical chemistry.',
                        'institutions' => ['UI', 'UNILAG', 'OAU', 'UNN', 'UNILORIN', 'ABU', 'UNIBEN'],
                    ],
                    [
                        'name' => 'Microbiology',
                        'slug' => 'microbiology',
                        'utme_subjects' => ['English Language', 'Biology', 'Chemistry', 'Physics'],
                        'utme_slugs' => ['english-language', 'biology', 'chemistry', 'physics'],
                        'olevel_requirements' => '5 SSCE credit passes in English Language, Mathematics, Chemistry, Biology, and Physics.',
                        'remarks' => 'Pathology, immunology, industrial fermentation and virology.',
                        'institutions' => ['UNILAG', 'UI', 'OAU', 'ABU', 'UNN', 'UNILORIN', 'FUTA'],
                    ],
                    [
                        'name' => 'Industrial Chemistry / Pure Chemistry',
                        'slug' => 'chemistry',
                        'utme_subjects' => ['English Language', 'Chemistry', 'Physics', 'Mathematics'],
                        'utme_slugs' => ['english-language', 'chemistry', 'physics', 'mathematics'],
                        'olevel_requirements' => '5 SSCE credit passes in English Language, Mathematics, Chemistry, Physics, and Biology.',
                        'remarks' => 'Analytical instrumentation, polymer synthesis and environmental chemistry.',
                        'institutions' => ['UI', 'UNILAG', 'OAU', 'FUTA', 'UNIBEN', 'UNILORIN'],
                    ],
                    [
                        'name' => 'Physics / Electronics',
                        'slug' => 'physics',
                        'utme_subjects' => ['English Language', 'Physics', 'Mathematics', 'Chemistry'],
                        'utme_slugs' => ['english-language', 'physics', 'mathematics', 'chemistry'],
                        'olevel_requirements' => '5 SSCE credit passes in English Language, Mathematics, Physics, Chemistry, and Biology/Geography.',
                        'remarks' => 'Theoretical physics, optics, geophysics and renewable energy.',
                        'institutions' => ['UI', 'OAU', 'UNILAG', 'UNN', 'ABU', 'FUTA'],
                    ],
                ],
            ],

            'social_management' => [
                'name' => 'Social & Management Sciences',
                'icon' => '📈',
                'description' => 'Accounting, Economics, Business Administration, Mass Communication, Political Science and Banking.',
                'courses' => [
                    [
                        'name' => 'Accounting',
                        'slug' => 'accounting',
                        'utme_subjects' => ['English Language', 'Mathematics', 'Economics', 'Financial Accounting'],
                        'utme_slugs' => ['english-language', 'mathematics', 'economics', 'financial-accounting'],
                        'olevel_requirements' => '5 SSCE credit passes in English Language, Mathematics, Economics, and two other commercial/arts subjects.',
                        'remarks' => 'Commerce or Government can be substituted for Financial Accounting in many universities.',
                        'institutions' => ['UNILAG', 'UI', 'ABU', 'OAU', 'UNN', 'UNILORIN', 'UNIBEN', 'BUK'],
                    ],
                    [
                        'name' => 'Economics',
                        'slug' => 'economics',
                        'utme_subjects' => ['English Language', 'Economics', 'Mathematics', 'Government'],
                        'utme_slugs' => ['english-language', 'economics', 'mathematics', 'government'],
                        'olevel_requirements' => '5 SSCE credit passes in English Language, Mathematics, Economics, and two other social science subjects.',
                        'remarks' => 'Credit pass in Mathematics is strictly compulsory for Economics in all accredited universities.',
                        'institutions' => ['UI', 'UNILAG', 'OAU', 'UNN', 'ABU', 'UNILORIN', 'LASU'],
                    ],
                    [
                        'name' => 'Business Administration / Management',
                        'slug' => 'business-administration',
                        'utme_subjects' => ['English Language', 'Mathematics', 'Economics', 'Commerce'],
                        'utme_slugs' => ['english-language', 'mathematics', 'economics', 'commerce'],
                        'olevel_requirements' => '5 SSCE credit passes in English Language, Mathematics, Economics, and two other commercial subjects.',
                        'remarks' => 'Government can be chosen in place of Commerce.',
                        'institutions' => ['UNILAG', 'ABU', 'UNN', 'OAU', 'UNILORIN', 'BUK', 'UNIBEN'],
                    ],
                    [
                        'name' => 'Mass Communication / Media Studies',
                        'slug' => 'mass-communication',
                        'utme_subjects' => ['English Language', 'Literature in English', 'Government', 'Economics'],
                        'utme_slugs' => ['english-language', 'literature-in-english', 'government', 'economics'],
                        'olevel_requirements' => '5 SSCE credit passes in English Language, Mathematics, Literature in English, and two other social/arts subjects.',
                        'remarks' => 'Christian/Islamic Religious Studies or Commerce is also accepted in place of Economics.',
                        'institutions' => ['UNILAG', 'UNN', 'UI', 'UNILORIN', 'BUK', 'COVENANT', 'LASU'],
                    ],
                    [
                        'name' => 'Political Science / International Relations',
                        'slug' => 'political-science',
                        'utme_subjects' => ['English Language', 'Government', 'Economics', 'Literature in English'],
                        'utme_slugs' => ['english-language', 'government', 'economics', 'literature-in-english'],
                        'olevel_requirements' => '5 SSCE credit passes in English Language, Mathematics, Government/History, and two other arts or social science subjects.',
                        'remarks' => 'CRS/IRS or Geography can also be substituted for Literature.',
                        'institutions' => ['UI', 'UNILAG', 'OAU', 'ABU', 'UNN', 'UNILORIN', 'BUK'],
                    ],
                ],
            ],

            'law' => [
                'name' => 'Faculty of Law (LL.B)',
                'icon' => '⚖️',
                'description' => 'Civil Law, Common Law, Islamic Law, Commercial and International Law.',
                'courses' => [
                    [
                        'name' => 'Law / Common Law (LL.B)',
                        'slug' => 'law',
                        'utme_subjects' => ['English Language', 'Literature in English', 'Government', 'Christian Religious Studies'],
                        'utme_slugs' => ['english-language', 'literature-in-english', 'government', 'christian-religious-studies'],
                        'olevel_requirements' => '5 SSCE credit passes in English Language, Literature in English, Mathematics, and two other arts or social science subjects in not more than one sitting.',
                        'remarks' => 'Literature in English is mandatory. Cut-off marks are usually very high (270+).',
                        'institutions' => ['UI', 'UNILAG', 'OAU', 'ABU', 'UNN', 'UNILORIN', 'UNIBEN', 'LASU'],
                    ],
                    [
                        'name' => 'Islamic Law / Sharia Law',
                        'slug' => 'islamic-law',
                        'utme_subjects' => ['English Language', 'Islamic Religious Studies', 'Government', 'Literature in English'],
                        'utme_slugs' => ['english-language', 'islamic-religious-studies', 'government', 'literature-in-english'],
                        'olevel_requirements' => '5 SSCE credit passes in English Language, Islamic Religious Studies, Mathematics, and two other subjects.',
                        'remarks' => 'Arabic is accepted or required in northern state and federal universities.',
                        'institutions' => ['ABU', 'BUK', 'UNILORIN', 'UDUSOK', 'KWASU', 'KASU'],
                    ],
                ],
            ],

            'arts' => [
                'name' => 'Arts & Humanities',
                'icon' => '🎭',
                'description' => 'English & Literary Studies, History & Diplomatic Studies, Philosophy, Linguistics, Theatre Arts.',
                'courses' => [
                    [
                        'name' => 'English and Literary Studies',
                        'slug' => 'english-and-literary-studies',
                        'utme_subjects' => ['English Language', 'Literature in English', 'Government', 'Christian Religious Studies'],
                        'utme_slugs' => ['english-language', 'literature-in-english', 'government', 'christian-religious-studies'],
                        'olevel_requirements' => '5 SSCE credit passes in English Language, Literature in English, Mathematics, and two other arts subjects.',
                        'remarks' => 'IRS or History can substitute for CRS.',
                        'institutions' => ['UI', 'UNILAG', 'UNN', 'OAU', 'ABU', 'UNILORIN', 'UNIBEN'],
                    ],
                    [
                        'name' => 'History and International Studies',
                        'slug' => 'history-international-studies',
                        'utme_subjects' => ['English Language', 'Government', 'Literature in English', 'Economics'],
                        'utme_slugs' => ['english-language', 'government', 'literature-in-english', 'economics'],
                        'olevel_requirements' => '5 SSCE credit passes in English Language, Government/History, Mathematics, and two arts/social sciences.',
                        'remarks' => 'CRS or IRS is also widely accepted in place of Economics.',
                        'institutions' => ['UI', 'UNILAG', 'OAU', 'UNN', 'ABU', 'UNILORIN'],
                    ],
                    [
                        'name' => 'Theatre & Performing Arts',
                        'slug' => 'theatre-arts',
                        'utme_subjects' => ['English Language', 'Literature in English', 'Government', 'Christian Religious Studies'],
                        'utme_slugs' => ['english-language', 'literature-in-english', 'government', 'christian-religious-studies'],
                        'olevel_requirements' => '5 SSCE credit passes in English Language, Literature in English, Mathematics, and two arts subjects.',
                        'remarks' => 'Audition and practical interview may be required during post-UTME screening.',
                        'institutions' => ['UI', 'UNILAG', 'UNN', 'UNICAL', 'UNIBEN', 'DELSU'],
                    ],
                ],
            ],

            'agriculture' => [
                'name' => 'Agriculture & Environmental Sciences',
                'icon' => '🌱',
                'description' => 'Agricultural Science, Food Science, Forestry, Soil Science, Architecture, Estate Management.',
                'courses' => [
                    [
                        'name' => 'Agricultural Science / Agronomy',
                        'slug' => 'agricultural-science',
                        'utme_subjects' => ['English Language', 'Agricultural Science', 'Chemistry', 'Biology'],
                        'utme_slugs' => ['english-language', 'agricultural-science', 'chemistry', 'biology'],
                        'olevel_requirements' => '5 SSCE credit passes in English Language, Mathematics, Chemistry, Agricultural Science or Biology, and Physics.',
                        'remarks' => 'Physics or Mathematics can replace Agricultural Science in UTME depending on the university.',
                        'institutions' => ['FUNAAB', 'UI', 'ABU', 'UNILORIN', 'OAU', 'UNN', 'FUTO'],
                    ],
                    [
                        'name' => 'Architecture / Estate Management',
                        'slug' => 'architecture',
                        'utme_subjects' => ['English Language', 'Mathematics', 'Physics', 'Geography'],
                        'utme_slugs' => ['english-language', 'mathematics', 'physics', 'geography'],
                        'olevel_requirements' => '5 SSCE credit passes in English Language, Mathematics, Physics, and two of Chemistry, Geography, Economics, or Technical Drawing.',
                        'remarks' => 'Chemistry is accepted in place of Geography.',
                        'institutions' => ['ABU', 'UNILAG', 'OAU', 'FUTA', 'UNN', 'FUTO', 'CRAWFORD'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Search courses by keyword or faculty.
     */
    public static function search(string $keyword = '', string $facultyKey = ''): array
    {
        $allFaculties = self::getFaculties();
        $results = [];

        $keyword = trim(strtolower($keyword));

        foreach ($allFaculties as $key => $faculty) {
            if ($facultyKey && $key !== $facultyKey) {
                continue;
            }

            foreach ($faculty['courses'] as $course) {
                if ($keyword === '') {
                    $results[] = array_merge($course, ['faculty_name' => $faculty['name'], 'faculty_icon' => $faculty['icon']]);
                    continue;
                }

                $matchCourse = str_contains(strtolower($course['name']), $keyword);
                $matchSubjects = collect($course['utme_subjects'])->some(fn($s) => str_contains(strtolower($s), $keyword));
                $matchFaculty = str_contains(strtolower($faculty['name']), $keyword);
                $matchInstitutions = collect($course['institutions'])->some(fn($i) => str_contains(strtolower($i), $keyword));

                if ($matchCourse || $matchSubjects || $matchFaculty || $matchInstitutions) {
                    $results[] = array_merge($course, ['faculty_name' => $faculty['name'], 'faculty_icon' => $faculty['icon']]);
                }
            }
        }

        return $results;
    }

    /**
     * Find a single course by its slug.
     */
    public static function findBySlug(string $slug): ?array
    {
        foreach (self::getFaculties() as $faculty) {
            foreach ($faculty['courses'] as $course) {
                if ($course['slug'] === $slug) {
                    return array_merge($course, ['faculty_name' => $faculty['name'], 'faculty_icon' => $faculty['icon']]);
                }
            }
        }

        return null;
    }

    /**
     * Map course UTME slugs directly to Subject database IDs for an Exam.
     */
    public static function mapCourseToSubjectIds(string $courseSlug, int $examId): array
    {
        $course = self::findBySlug($courseSlug);
        if (!$course) {
            return [];
        }

        return \App\Models\Subject::where('exam_id', $examId)
            ->whereIn('slug', $course['utme_slugs'])
            ->pluck('id')
            ->map(fn($id) => (string) $id)
            ->toArray();
    }
}
