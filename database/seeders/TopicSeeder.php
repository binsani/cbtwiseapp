<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TopicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $curriculumTopics = [
            'english-language' => [
                'Comprehension & Summary Passages',
                'Lexis and Structure',
                'Synonyms and Antonyms',
                'Idioms and Figurative Expressions',
                'Sentence Completion',
                'Vowels and Consonants (Oral Forms)',
                'Stress Patterns & Intonation',
                'Prescribed Life Changer / Novel Studies',
            ],
            'mathematics' => [
                'Number and Numeration (Fractions, Indices, Logarithms)',
                'Algebraic Expressions & Factorization',
                'Linear & Quadratic Equations',
                'Simultaneous & Polynomial Equations',
                'Surds, Matrices & Determinants',
                'Arithmetic & Geometric Progressions (AP/GP)',
                'Set Theory & Venn Diagrams',
                'Euclidean Geometry & Circle Theorems',
                'Trigonometry & Angles of Elevation/Depression',
                'Calculus: Differentiation & Integration',
                'Statistics: Mean, Median, Mode & Dispersion',
                'Probability & Permutations/Combinations',
            ],
            'physics' => [
                'Measurements, Units & Dimensions',
                'Kinematics: Rectilinear & Projectile Motion',
                'Dynamics: Newton\'s Laws, Momentum & Friction',
                'Work, Energy and Power',
                'Circular & Simple Harmonic Motion (SHM)',
                'Fluid Statics: Density, Pressure & Archimedes Principle',
                'Thermal Physics: Heat Transfer, Expansivity & Gas Laws',
                'Wave Motion, Sound & Acoustics',
                'Optics: Reflection, Refraction & Optical Instruments',
                'Electrostatics, Coulomb\'s Law & Capacitors',
                'Current Electricity: Ohm\'s Law, Circuits & Heating',
                'Electromagnetism & AC Circuits',
                'Atomic & Nuclear Physics (Radioactivity)',
            ],
            'chemistry' => [
                'Particulate Nature of Matter & Atomic Structure',
                'Chemical Bonding & Intermolecular Forces',
                'Periodic Table & Periodicity of Elements',
                'Stoichiometry & Mole Concept',
                'Gas Laws & Kinetic Theory of Matter',
                'Acids, Bases, Salts & Indicators',
                'Oxidation-Reduction (Redox) Reactions & Electrolysis',
                'Chemical Energetics & Thermochemistry',
                'Chemical Equilibrium & Le Chatelier\'s Principle',
                'Non-Metals and their Compounds (Halogens, Nitrogen, Sulphur)',
                'Metals and their Compounds (Extraction & Reactions)',
                'Organic Chemistry: Hydrocarbons (Alkanes, Alkenes, Alkynes)',
                'Alkanols, Alkanoic Acids, Esters & Polymers',
            ],
            'biology' => [
                'Cell Biology: Structure, Organization & Organelles',
                'Classification of Living Organisms (Monera to Animalia)',
                'Plant Nutrition: Photosynthesis & Mineral Requirements',
                'Animal Nutrition & Digestive Systems',
                'Transport Systems in Plants & Animals (Blood & Xylem/Phloem)',
                'Respiratory Systems & Gaseous Exchange',
                'Excretion and Osmoregulation',
                'Nervous Coordination & Sense Organs',
                'Endocrine System & Hormonal Control',
                'Reproduction in Flowering Plants & Mammals',
                'Genetics, Heredity & Mendel\'s Laws',
                'Ecology: Ecosystems, Biomes & Nutrient Cycles',
                'Evolution, Natural Selection & Adaptation',
            ],
            'economics' => [
                'Basic Concepts of Economics (Scarcity, Choice, Opportunity Cost)',
                'Theory of Consumer Behaviour & Utility',
                'Theory of Demand and Supply & Elasticity',
                'Theory of Production & Cost Functions',
                'Market Structures (Perfect Competition, Monopoly, Oligopoly)',
                'National Income Accounting (GDP, GNP, NNP)',
                'Money, Commercial & Central Banking',
                'Inflation, Deflation & Monetary Policy',
                'Public Finance, Taxation & Fiscal Policy',
                'International Trade & Balance of Payments',
                'Economic Growth and Development in Nigeria',
            ],
            'government' => [
                'Basic Concepts: Sovereignty, Power, Authority & Legitimacy',
                'Forms and Systems of Government (Unitary, Federal, Parliamentary)',
                'Rule of Law & Separation of Powers',
                'Political Parties, Pressure Groups & Electoral Systems',
                'Pre-Colonial Administration in Nigeria (Hausa/Fulani, Yoruba, Igbo)',
                'Colonial Administration & Indirect Rule in Nigeria',
                'Constitutional Developments: Clifford, Richards, Macpherson, 1999',
                'Federalism and Intergovernmental Relations in Nigeria',
                'Nigeria\'s Foreign Policy & International Organizations (UN, AU, ECOWAS)',
            ],
            'literature-in-english' => [
                'Literary Appreciation & Figures of Speech',
                'Prescribed African Prose',
                'Prescribed Non-African Prose',
                'Prescribed African Drama',
                'Prescribed Non-African Drama (Shakespeare)',
                'Prescribed African Poetry',
                'Prescribed Non-African Poetry',
            ],
            'commerce' => [
                'Introduction to Commerce & Trade Channels',
                'Home Trade: Wholesale & Retail Trade',
                'Foreign Trade: Export, Import & Customs',
                'Aids to Trade: Advertising, Transportation & Warehousing',
                'Banking, Insurance and Finance',
                'Business Units: Sole Trader, Partnership, Limited Companies',
            ],
            'financial-accounting' => [
                'Accounting Concepts, Principles & Conventions',
                'Books of Original Entry & Ledgers',
                'Trial Balance and Correction of Errors',
                'Trading, Profit and Loss Account & Balance Sheet',
                'Bank Reconciliation Statements',
                'Depreciation of Fixed Assets & Reserves',
                'Partnership and Company Accounts',
            ],
            'agricultural-science' => [
                'Importance of Agriculture & Farming Systems',
                'Soil Science: Types, Structure, Fertility & Management',
                'Crop Production: Cereals, Legumes, Tubers & Vegetables',
                'Crop Diseases, Pests and Weed Control',
                'Animal Husbandry: Cattle, Sheep, Goat, Poultry & Fisheries',
                'Agricultural Economics and Extension Services',
            ],
            'civic-education' => [
                'Values, Citizenship, Rights and Obligations',
                'National Consciousness, Unity and Identity',
                'Democracy, Rule of Law and Constitutional Governance',
                'Human Rights & Fundamental Freedoms',
                'Social Issues: Cultism, Drug Abuse, HIV/AIDS & Human Trafficking',
            ],
        ];

        $subjects = \App\Models\Subject::all();

        foreach ($subjects as $subject) {
            $slug = $subject->slug;
            if (isset($curriculumTopics[$slug])) {
                $order = 1;
                foreach ($curriculumTopics[$slug] as $topicName) {
                    \App\Models\Topic::firstOrCreate(
                        [
                            'subject_id' => $subject->id,
                            'name' => $topicName,
                        ],
                        [
                            'sort_order' => $order++,
                        ]
                    );
                }
            }
        }
    }
}
