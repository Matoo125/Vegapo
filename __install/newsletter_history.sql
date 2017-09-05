CREATE TABLE newsletter_history (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `newsletter` varchar(255) NOT NULL, -- id of newsletter
  `email` varchar(255) NOT NULL, -- email of recipient
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)