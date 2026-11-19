INSERT INTO personal_information
(id, full_name, introduction, biography, location, email, profile_image)
VALUES
(1, 'Paul Valde', 'Information Technology student and beginner web developer.', 'I am a BSIT student from the Philippines who builds simple and useful websites. I enjoy web design, networking, photography, and learning new technology.', 'Purok 6, Basay, Negros Oriental', 'paulvalde033@gmail.com', 'images/picture4.jpg');

INSERT INTO skills (name, category, proficiency, sort_order) VALUES
('HTML5', 'Web Development', 75, 1),
('CSS', 'Web Development', 85, 2),
('PHP', 'Web Development', 70, 3),
('Cisco Packet Tracer', 'Networking', 80, 4),
('Adobe Illustrator', 'Design', 65, 5),
('Capcut', 'Video Editing', 90, 6),
('Photography', 'Creative', 95, 7),
('UI Design', 'Design', 70, 8);

INSERT INTO projects (title, description, category, image_path, sort_order) VALUES
('Cisco Packet Tracer', 'A comprehensive network diagram showing connections and configurations designed for efficient data flow and communication.', 'web', 'images/picture1.png', 1),
('My First Website', 'A photographer management system built to organize photo shoots, client bookings, galleries, and schedules.', 'web', 'images/picture2.png', 2),
('Adobe Illustrator', 'A visual design project created using Adobe Illustrator.', 'design', 'images/picture3.png', 3),
('Adobe Animate', 'A short animated advertisement created with motion tweens and transitions.', 'design', 'images/picture5.png', 4);

INSERT INTO education (institution, qualification, description, sort_order) VALUES
('College', 'BS Information Technology', 'Built a strong foundation in programming, databases, networking, system analysis, web development, and multimedia.', 1);

INSERT INTO experience (position, organization, description, sort_order) VALUES
('Student Developer', 'Academic Projects', 'Gained practical experience through academic projects involving web development, networking, multimedia, and collaborative software development.', 1);

INSERT INTO achievements (title, organization, description, sort_order) VALUES
('Academic Portfolio', 'School', 'Completed personal and academic projects involving web design, networking, and multimedia.', 1);

INSERT INTO website_settings (setting_key, setting_value) VALUES
('website_title', 'PAUL | Portfolio'),
('footer_text', '© 2026 Paul Valde. PERSONAL PORTFOLIO.');
