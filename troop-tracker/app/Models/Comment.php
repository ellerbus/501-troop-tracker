<?php

namespace App\Models;

use App\Models\Base\Comment as BaseComment;

class Comment extends BaseComment
{
	protected $fillable = [
		'troopid',
		'trooperid',
		'post_id',
		'comment',
		'posted'
	];
}
