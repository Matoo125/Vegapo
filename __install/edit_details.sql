# prehlad editov z roznych zdrojov - pracovna verzia, stlpce sa budu menit podla potreby
CREATE or replace VIEW edit_details AS 
	#locale
	SELECT 
		q.id edit_id, q.type edit_type, q.state edit_state, q.user_id edit_user_id, w.username edit_username, q.comment edit_comment, q.diff edit_diff, q.country edit_country, q.created_at edit_created_at, q.updated_at edit_updated_at,
		q.object_type, q.object_id, null object_user_id, null objec_username
	FROM edits q, users w 
	where q.object_type = 'locale' and q.user_id = w.user_id
	union
	#suggestions 
	SELECT 
		q.id edit_id, q.type edit_type, q.state edit_state, q.user_id edit_user_id, e.username edit_username, q.comment edit_comment, q.diff edit_diff, q.country edit_country, q.created_at edit_created_at, q.updated_at edit_updated_at,
		q.object_type, q.object_id,  w.user_id object_user_id, r.username object_username
	FROM edits q, suggestions w, users e, users r
	where q.object_type = 'suggestions' and q.object_id = w.id and q.user_id = e.user_id and w.user_id = r.user_id 
;


