DROP TABLE IF EXISTS `birds`;
CREATE TABLE `birds` (
  `id` int NOT NULL AUTO_INCREMENT,
  `common_name` varchar(100) NOT NULL,
  `habitat` varchar(100) NOT NULL,
  `food` varchar(100) NOT NULL,
  `conservation_id` tinyint NOT NULL,
  `backyard_tips` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

INSERT INTO `birds` (`id`, `common_name`, `habitat`, `food`, `conservation_id`, `backyard_tips`) VALUES
('3', 'Carolina Wren', 'Open woodlands', 'Insects', '1', 'Carolina Wrens visit suet-filled feeders during winter.'),
('4', 'Tufted Titmouse', 'Forests', 'Insects', '1', 'Tufted Titmouse are regulars at backyard bird feeders, especially in winter. They prefer sunflower seeds but will eat suet, peanuts, and other seeds as well.'),
('5', 'Ruby-Throated Hummingbird', 'Open woodlands', 'Nectar', '1', 'You can attract Ruby-throated Hummingbirds to your backyard by setting up hummingbird feeders or by planting tubular flowers.'),
('6', 'Eastern Towhee', 'Scrub', 'Omnivore', '1', 'Eastern Towhees are likely to visit – or perhaps live in – your yard if you’ve got brushy, shrubby, or overgrown borders.'),
('7', 'Indigo Bunting', 'Open woodlands', 'Insects', '1', 'You can attract Indigo Buntings to your yard with feeders, particularly with small seeds such as thistle or nyjer.');
