<?php

return [
    'title' => 'Polls',
    'singular' => 'Poll',
    'mine' => 'My votes',

    'fields' => [
        'title' => 'Title',
        'description' => 'Description',
        'is_active' => 'Active',
        'starts_at' => 'Starts at',
        'ends_at' => 'Ends at',
        'allow_multiple' => 'Allow multiple choices',
        'options' => 'Options',
        'option_text' => 'Option text',
        'order' => 'Order',
        'created_by' => 'Author',
        'created_at' => 'Created',
    ],

    'status' => [
        'active' => 'Active',
        'inactive' => 'Inactive',
        'finished' => 'Finished',
        'scheduled' => 'Scheduled',
    ],

    'actions' => [
        'create' => 'Create poll',
        'edit' => 'Edit poll',
        'delete' => 'Delete poll',
        'view' => 'View',
        'vote' => 'Vote',
        'results' => 'Results',
        'add_option' => 'Add option',
        'edit_option' => 'Edit option',
        'delete_option' => 'Delete option',
        'view_voters' => 'See voters',
    ],

    'list' => [
        'admin_heading' => 'All polls',
        'voter_heading' => 'Active polls',
        'empty' => 'No polls yet.',
        'votes_count' => 'Votes: :count',
        'options_count' => 'Options: :count',
    ],

    'show' => [
        'heading' => 'Poll details',
        'options' => 'Options',
        'results' => 'Results',
        'no_options' => 'No options have been added to this poll yet.',
        'already_voted' => 'You have already voted in this poll.',
        'not_active' => 'This poll is not active.',
        'select_one' => 'Pick one option',
        'select_multiple' => 'You may pick multiple options',
        'submit_vote' => 'Cast vote',
    ],

    'results' => [
        'total_votes' => 'Total votes: :count',
        'percentage' => ':percent%',
        'no_votes' => 'No votes yet.',
        'voters_list' => 'Who voted',
    ],

    'flash' => [
        'created' => 'Poll created.',
        'updated' => 'Poll updated.',
        'deleted' => 'Poll deleted.',
        'option_created' => 'Option added.',
        'option_updated' => 'Option updated.',
        'option_deleted' => 'Option deleted.',
        'vote_recorded' => 'Your vote has been recorded.',
        'vote_already' => 'You have already voted for this option.',
        'vote_not_active' => 'This poll is not active.',
    ],
];
