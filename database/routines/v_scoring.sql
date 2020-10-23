create view v_scoring as
(select s.id as scoring_id,
		s.name as scoring_name,
		s.description as scoring_des,

        c.id as cat_id,
        c.name as cat_name,
        c.description as cat_des,
        c.feed_back as cat_feed,

        d.id as det_id,
        d.name as det_name,
        d.description as det_des,
        d.feed_back as det_feed,
        d.points,
        d.method
	FROM scoring s
		JOIN cat_scoring c ON (s.id = c.scoring_id)
        JOIN det_scoring d ON (c.id = d.cat_scoring_id))
