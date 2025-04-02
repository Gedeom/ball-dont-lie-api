CREATE DATABASE IF NOT EXISTS db_ball_dont_lie;
CREATE DATABASE IF NOT EXISTS db_ball_dont_lie_test;

GRANT ALL PRIVILEGES ON db_ball_dont_lie.* TO 'root'@'%';
GRANT ALL PRIVILEGES ON db_ball_dont_lie_test.* TO 'root'@'%';

FLUSH PRIVILEGES;