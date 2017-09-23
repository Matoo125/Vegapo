# prehlad editov z roznych zdrojov - pracovna verzia, stlpce sa budu menit podla potreby
CREATE or replace VIEW edit_details AS 
	#locale
	SELECT 
		q.id edit_id, q.type edit_type, null edit_sub_type, q.state edit_state, q.user_id edit_user_id, w.username edit_username, q.comment edit_comment, q.diff edit_diff, q.country edit_country, q.created_at edit_created_at, q.updated_at edit_updated_at,
		q.object_type, q.object_id, null object_name, null object_user_id, null object_username
	FROM edits q, users w 
	where q.object_type = 'locale' and q.user_id = w.user_id
	union
	#suggestion
	SELECT 
		q.id edit_id, q.type edit_type, w.type edit_sub_type, q.state edit_state, q.user_id edit_user_id, e.username edit_username, q.comment edit_comment, q.diff edit_diff, q.country edit_country, q.created_at edit_created_at, q.updated_at edit_updated_at,
		'product' object_type, w.product_id object_id, t.name object_name,  w.user_id object_user_id, r.username object_username
	FROM edits q, suggestions w, users e, users r, products t
	where q.object_type = 'suggestion' and q.object_id = w.id and q.user_id = e.user_id and w.user_id = r.user_id and t.id= w.product_id
	union
	#product	
	SELECT 
		q.id edit_id, q.type edit_type, null edit_sub_type, q.state edit_state, q.user_id edit_user_id, e.username edit_username, q.comment edit_comment, q.diff edit_diff, q.country edit_country, q.created_at edit_created_at, q.updated_at edit_updated_at,
		q.object_type, w.id object_id, w.name object_name,  w.author_id object_user_id, r.username object_username
	FROM edits q, products w, users e, users r
	where q.object_type = 'product' and q.object_id = w.id and q.user_id = e.user_id and w.author_id = r.user_id 
	union
	#user
	SELECT 
		q.id edit_id, q.type edit_type, null edit_sub_type, q.state edit_state, q.user_id edit_user_id, e.username edit_username, q.comment edit_comment, q.diff edit_diff, q.country edit_country, q.created_at edit_created_at, q.updated_at edit_updated_at,
		q.object_type, w.user_id object_id, w.username object_name,  null object_user_id, null object_username
	FROM edits q, users w, users e
	where q.object_type = 'user' and q.object_id = w.user_id and q.user_id = e.user_id 
	union
	#info
	SELECT 
		q.id edit_id, q.type edit_type, null edit_sub_type, q.state edit_state, q.user_id edit_user_id, w.username edit_username, null edit_comment, q.diff edit_diff, q.country edit_country, q.created_at edit_created_at, q.updated_at edit_updated_at,
		q.object_type, q.comment object_id, q.comment object_name, null object_user_id, null object_username
	FROM edits q, users w 
	where q.object_type = 'info' and q.user_id = w.user_id	
	union
	#tag
	SELECT 
		q.id edit_id, q.type edit_type, null edit_sub_type, q.state edit_state, q.user_id edit_user_id, e.username edit_username, q.comment edit_comment, q.diff edit_diff, q.country edit_country, q.created_at edit_created_at, q.updated_at edit_updated_at,
		q.object_type, w.id object_id, w.name object_name,  e.user_id object_user_id, e.username object_username
	FROM edits q, tags w, users e
	where q.object_type = 'tag' and q.object_id = w.id and q.user_id = e.user_id 
	union
	#store
	SELECT 
		q.id edit_id, q.type edit_type, null edit_sub_type, q.state edit_state, q.user_id edit_user_id, e.username edit_username, q.comment edit_comment, q.diff edit_diff, q.country edit_country, q.created_at edit_created_at, q.updated_at edit_updated_at,
		q.object_type, w.id object_id, w.name object_name,  e.user_id object_user_id, e.username object_username
	FROM edits q, supermarkets w, users e
	where q.object_type = 'store' and q.object_id = w.id and q.user_id = e.user_id 
;


