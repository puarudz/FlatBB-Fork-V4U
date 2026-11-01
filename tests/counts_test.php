<?php
/** Counters after content is deleted or restored (FlatBB bug "Post count issue"): a member's topic and reply numbers follow what is visible. Run with: php flatbb test */

function counts_test_setup(string $suffix): array
{
    save_settings(['review_first_posts' => '0']);
    $cat = (int)val('SELECT id FROM fb_categories ORDER BY id LIMIT 1');
    $author = user_create('cnt_author_' . $suffix, 'cnt_author_' . $suffix . '@example.invalid', 'password-123');
    $replier = user_create('cnt_replier_' . $suffix, 'cnt_replier_' . $suffix . '@example.invalid', 'password-123');
    return [$cat, $author, $replier];
}

function test_deleting_a_reply_lowers_the_author_reply_count_and_restoring_raises_it(): void
{
    [$cat, $author, $replier] = counts_test_setup('reply');
    $tid = topic_create($cat, $author, 'Counting replies with narwhalware', 'A made-up word keeps this topic unique.');
    test_same(1, (int)val('SELECT topic_count FROM fb_users WHERE id=?', [$author]), 'the new topic counts for its author');
    test_same(0, (int)val('SELECT post_count FROM fb_users WHERE id=?', [$author]), 'the first post is not a reply');
    $pid = post_create((array)topic_by_id($tid), $replier, 'A reply that will be deleted and restored.');
    test_same(1, (int)val('SELECT post_count FROM fb_users WHERE id=?', [$replier]), 'the reply counts');
    test_same(1, (int)val('SELECT reply_count FROM fb_topics WHERE id=?', [$tid]), 'the topic counts the reply');
    post_set_deleted((array)post_by_id($pid), (array)topic_by_id($tid), true);
    test_same(0, (int)val('SELECT post_count FROM fb_users WHERE id=?', [$replier]), 'a deleted reply does not count');
    test_same(0, (int)val('SELECT reply_count FROM fb_topics WHERE id=?', [$tid]), 'nor in the topic');
    post_set_deleted((array)post_by_id($pid), (array)topic_by_id($tid), false);
    test_same(1, (int)val('SELECT post_count FROM fb_users WHERE id=?', [$replier]), 'a restored reply counts again');
    test_same(1, (int)val('SELECT reply_count FROM fb_topics WHERE id=?', [$tid]), 'and in the topic');
}

function test_deleting_a_topic_lowers_the_author_topic_count_and_restoring_raises_it(): void
{
    [$cat, $author] = counts_test_setup('topic');
    $tid = topic_create($cat, $author, 'Counting topics with axolotlware', 'Another made-up word.');
    test_same(1, (int)val('SELECT topic_count FROM fb_users WHERE id=?', [$author]), 'counted after it went public');
    topic_set_deleted((array)topic_by_id($tid), true);
    test_same(0, (int)val('SELECT topic_count FROM fb_users WHERE id=?', [$author]), 'a deleted topic does not count');
    topic_set_deleted((array)topic_by_id($tid), false);
    test_same(1, (int)val('SELECT topic_count FROM fb_users WHERE id=?', [$author]), 'restored: counted again');
}
