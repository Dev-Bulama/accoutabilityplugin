<?php
/**
 * Form Templates Configuration
 *
 * Provides pre-built form templates for different use cases
 *
 * @package Altitude_Audit
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Get all available form templates.
 *
 * @return array Array of templates.
 */
function altitude_audit_get_templates() {
	return array(
		'accountability_audit' => array(
			'name' => __( 'Personal Accountability Audit', 'altitude-accountability-audit' ),
			'description' => __( 'Comprehensive self-assessment quiz measuring accountability across 6 key areas: Distraction, Comfort, Ego, Emotion, Boundaries, and Spiritual Growth.', 'altitude-accountability-audit' ),
			'icon' => '📋',
			'category' => __( 'Self-Assessment', 'altitude-accountability-audit' ),
			'categories' => array(
				'distraction' => array(
					'label' => 'Distraction',
					'description' => 'Measures your tendency to lose focus and be diverted from important tasks.',
					'icon' => '📱',
					'questions' => array(
						array(
							'label' => 'I pick up my phone without thinking and lose time.',
							'name' => 'distraction_q1',
							'help_text' => '',
						),
						array(
							'label' => 'I start tasks but rarely finish them before moving to something else.',
							'name' => 'distraction_q2',
							'help_text' => '',
						),
						array(
							'label' => 'I find myself scrolling social media when I should be working on priorities.',
							'name' => 'distraction_q3',
							'help_text' => '',
						),
					),
				),
				'comfort' => array(
					'label' => 'Comfort',
					'description' => 'Assesses how much you prioritize comfort over growth and challenge.',
					'icon' => '🛋️',
					'questions' => array(
						array(
							'label' => 'I avoid difficult conversations because they feel uncomfortable.',
							'name' => 'comfort_q1',
							'help_text' => '',
						),
						array(
							'label' => 'I choose easy tasks over challenging ones that would help me grow.',
							'name' => 'comfort_q2',
							'help_text' => '',
						),
						array(
							'label' => 'I procrastinate on tasks that feel intimidating or unfamiliar.',
							'name' => 'comfort_q3',
							'help_text' => '',
						),
					),
				),
				'ego' => array(
					'label' => 'Ego',
					'description' => 'Evaluates whether pride and self-image prevent you from growing.',
					'icon' => '👑',
					'questions' => array(
						array(
							'label' => 'I struggle to admit when I\'m wrong or made a mistake.',
							'name' => 'ego_q1',
							'help_text' => '',
						),
						array(
							'label' => 'I feel threatened when others succeed or receive recognition.',
							'name' => 'ego_q2',
							'help_text' => '',
						),
						array(
							'label' => 'I resist feedback because I take it personally.',
							'name' => 'ego_q3',
							'help_text' => '',
						),
					),
				),
				'emotion' => array(
					'label' => 'Emotion',
					'description' => 'Identifies whether emotions control your decisions and actions.',
					'icon' => '😤',
					'questions' => array(
						array(
							'label' => 'My mood determines whether I follow through on commitments.',
							'name' => 'emotion_q1',
							'help_text' => '',
						),
						array(
							'label' => 'I make impulsive decisions when I\'m feeling strong emotions.',
							'name' => 'emotion_q2',
							'help_text' => '',
						),
						array(
							'label' => 'I avoid responsibilities when I\'m not "feeling it."',
							'name' => 'emotion_q3',
							'help_text' => '',
						),
					),
				),
				'boundaries' => array(
					'label' => 'Boundaries',
					'description' => 'Determines if you maintain healthy limits with yourself and others.',
					'icon' => '🚧',
					'questions' => array(
						array(
							'label' => 'I say "yes" to things I should say "no" to.',
							'name' => 'boundaries_q1',
							'help_text' => '',
						),
						array(
							'label' => 'I let others\' needs consistently override my own priorities.',
							'name' => 'boundaries_q2',
							'help_text' => '',
						),
						array(
							'label' => 'I struggle to enforce consequences when my boundaries are crossed.',
							'name' => 'boundaries_q3',
							'help_text' => '',
						),
					),
				),
				'spiritual' => array(
					'label' => 'Spiritual',
					'description' => 'Assesses your commitment to spiritual practices and growth.',
					'icon' => '🙏',
					'questions' => array(
						array(
							'label' => 'I skip prayer or spiritual practices when life gets busy.',
							'name' => 'spiritual_q1',
							'help_text' => '',
						),
						array(
							'label' => 'My connection to my faith feels inconsistent or shallow.',
							'name' => 'spiritual_q2',
							'help_text' => '',
						),
						array(
							'label' => 'I rely on my own strength instead of seeking divine guidance.',
							'name' => 'spiritual_q3',
							'help_text' => '',
						),
					),
				),
			),
		),

		'employee_satisfaction' => array(
			'name' => __( 'Employee Satisfaction Survey', 'altitude-accountability-audit' ),
			'description' => __( 'Measure employee satisfaction across workplace culture, management, work-life balance, and career development.', 'altitude-accountability-audit' ),
			'icon' => '💼',
			'category' => __( 'HR & Workplace', 'altitude-accountability-audit' ),
			'categories' => array(
				'workplace_culture' => array(
					'label' => 'Workplace Culture',
					'description' => 'Assesses the overall work environment and team dynamics.',
					'icon' => '🏢',
					'questions' => array(
						array(
							'label' => 'I feel valued and appreciated by my team.',
							'name' => 'workplace_culture_q1',
							'help_text' => '',
						),
						array(
							'label' => 'Communication between departments is effective.',
							'name' => 'workplace_culture_q2',
							'help_text' => '',
						),
						array(
							'label' => 'The company culture aligns with my personal values.',
							'name' => 'workplace_culture_q3',
							'help_text' => '',
						),
					),
				),
				'management' => array(
					'label' => 'Management & Leadership',
					'description' => 'Evaluates the effectiveness of management and leadership.',
					'icon' => '👔',
					'questions' => array(
						array(
							'label' => 'My manager provides clear direction and expectations.',
							'name' => 'management_q1',
							'help_text' => '',
						),
						array(
							'label' => 'I receive regular feedback on my performance.',
							'name' => 'management_q2',
							'help_text' => '',
						),
						array(
							'label' => 'Leadership demonstrates genuine care for employee wellbeing.',
							'name' => 'management_q3',
							'help_text' => '',
						),
					),
				),
				'work_life_balance' => array(
					'label' => 'Work-Life Balance',
					'description' => 'Measures the balance between work and personal life.',
					'icon' => '⚖️',
					'questions' => array(
						array(
							'label' => 'I have adequate time for personal activities and family.',
							'name' => 'work_life_balance_q1',
							'help_text' => '',
						),
						array(
							'label' => 'The company respects my time outside of work hours.',
							'name' => 'work_life_balance_q2',
							'help_text' => '',
						),
						array(
							'label' => 'I feel energized rather than drained by my work.',
							'name' => 'work_life_balance_q3',
							'help_text' => '',
						),
					),
				),
				'career_development' => array(
					'label' => 'Career Development',
					'description' => 'Assesses opportunities for growth and advancement.',
					'icon' => '📈',
					'questions' => array(
						array(
							'label' => 'I see clear paths for advancement in my career.',
							'name' => 'career_development_q1',
							'help_text' => '',
						),
						array(
							'label' => 'The company invests in my professional development.',
							'name' => 'career_development_q2',
							'help_text' => '',
						),
						array(
							'label' => 'I am learning new skills that benefit my career.',
							'name' => 'career_development_q3',
							'help_text' => '',
						),
					),
				),
			),
		),

		'customer_feedback' => array(
			'name' => __( 'Customer Feedback Form', 'altitude-accountability-audit' ),
			'description' => __( 'Gather customer insights on product quality, service, value, and overall experience.', 'altitude-accountability-audit' ),
			'icon' => '⭐',
			'category' => __( 'Customer Experience', 'altitude-accountability-audit' ),
			'categories' => array(
				'product_quality' => array(
					'label' => 'Product Quality',
					'description' => 'Evaluates satisfaction with product features and quality.',
					'icon' => '📦',
					'questions' => array(
						array(
							'label' => 'The product meets my needs and expectations.',
							'name' => 'product_quality_q1',
							'help_text' => '',
						),
						array(
							'label' => 'The product is well-designed and easy to use.',
							'name' => 'product_quality_q2',
							'help_text' => '',
						),
						array(
							'label' => 'The product quality is consistent and reliable.',
							'name' => 'product_quality_q3',
							'help_text' => '',
						),
					),
				),
				'customer_service' => array(
					'label' => 'Customer Service',
					'description' => 'Assesses the quality of customer support interactions.',
					'icon' => '🎧',
					'questions' => array(
						array(
							'label' => 'Customer service representatives are knowledgeable and helpful.',
							'name' => 'customer_service_q1',
							'help_text' => '',
						),
						array(
							'label' => 'My issues are resolved quickly and efficiently.',
							'name' => 'customer_service_q2',
							'help_text' => '',
						),
						array(
							'label' => 'The support team is friendly and professional.',
							'name' => 'customer_service_q3',
							'help_text' => '',
						),
					),
				),
				'value_pricing' => array(
					'label' => 'Value & Pricing',
					'description' => 'Measures perceived value relative to cost.',
					'icon' => '💰',
					'questions' => array(
						array(
							'label' => 'The product offers good value for the price.',
							'name' => 'value_pricing_q1',
							'help_text' => '',
						),
						array(
							'label' => 'The pricing is fair and competitive.',
							'name' => 'value_pricing_q2',
							'help_text' => '',
						),
						array(
							'label' => 'I would purchase from this company again.',
							'name' => 'value_pricing_q3',
							'help_text' => '',
						),
					),
				),
				'overall_experience' => array(
					'label' => 'Overall Experience',
					'description' => 'General satisfaction with the entire customer journey.',
					'icon' => '✨',
					'questions' => array(
						array(
							'label' => 'My overall experience with the company is positive.',
							'name' => 'overall_experience_q1',
							'help_text' => '',
						),
						array(
							'label' => 'I would recommend this company to friends and family.',
							'name' => 'overall_experience_q2',
							'help_text' => '',
						),
						array(
							'label' => 'The company exceeded my expectations.',
							'name' => 'overall_experience_q3',
							'help_text' => '',
						),
					),
				),
			),
		),

		'skills_assessment' => array(
			'name' => __( 'Skills Assessment Quiz', 'altitude-accountability-audit' ),
			'description' => __( 'Evaluate proficiency in technical skills, communication, problem-solving, and teamwork.', 'altitude-accountability-audit' ),
			'icon' => '🎯',
			'category' => __( 'Professional Development', 'altitude-accountability-audit' ),
			'categories' => array(
				'technical_skills' => array(
					'label' => 'Technical Skills',
					'description' => 'Assesses proficiency in job-specific technical abilities.',
					'icon' => '💻',
					'questions' => array(
						array(
							'label' => 'I am proficient with the tools and technologies required for my role.',
							'name' => 'technical_skills_q1',
							'help_text' => '',
						),
						array(
							'label' => 'I can troubleshoot technical problems independently.',
							'name' => 'technical_skills_q2',
							'help_text' => '',
						),
						array(
							'label' => 'I stay updated with the latest trends in my field.',
							'name' => 'technical_skills_q3',
							'help_text' => '',
						),
					),
				),
				'communication' => array(
					'label' => 'Communication',
					'description' => 'Evaluates written and verbal communication abilities.',
					'icon' => '💬',
					'questions' => array(
						array(
							'label' => 'I express my ideas clearly and concisely.',
							'name' => 'communication_q1',
							'help_text' => '',
						),
						array(
							'label' => 'I actively listen and understand others\' perspectives.',
							'name' => 'communication_q2',
							'help_text' => '',
						),
						array(
							'label' => 'I adapt my communication style to different audiences.',
							'name' => 'communication_q3',
							'help_text' => '',
						),
					),
				),
				'problem_solving' => array(
					'label' => 'Problem Solving',
					'description' => 'Measures analytical thinking and solution-finding abilities.',
					'icon' => '🧩',
					'questions' => array(
						array(
							'label' => 'I approach challenges with a systematic problem-solving mindset.',
							'name' => 'problem_solving_q1',
							'help_text' => '',
						),
						array(
							'label' => 'I can break down complex problems into manageable parts.',
							'name' => 'problem_solving_q2',
							'help_text' => '',
						),
						array(
							'label' => 'I think creatively to find innovative solutions.',
							'name' => 'problem_solving_q3',
							'help_text' => '',
						),
					),
				),
				'teamwork' => array(
					'label' => 'Teamwork & Collaboration',
					'description' => 'Assesses ability to work effectively with others.',
					'icon' => '🤝',
					'questions' => array(
						array(
							'label' => 'I work well with diverse team members.',
							'name' => 'teamwork_q1',
							'help_text' => '',
						),
						array(
							'label' => 'I contribute positively to team goals and objectives.',
							'name' => 'teamwork_q2',
							'help_text' => '',
						),
						array(
							'label' => 'I support and help my teammates succeed.',
							'name' => 'teamwork_q3',
							'help_text' => '',
						),
					),
				),
			),
		),
	);
}
