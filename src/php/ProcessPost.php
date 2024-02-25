<?php

class ProcessPost
{
    function __construct(protected $postSlug, protected $postData)
    {
    }

    public function createPosts()
    {
        $this->deleteAllPost();
        return $this->createNewPosts();
    }

    private function deleteAllPost()
    {
        $posts = $this->getPosts();

        foreach ($posts as $post) {
            wp_delete_post($post->ID, true);
        }
    }

    private function createNewPosts()
    {
        try {
            foreach ($this->postData as $data) {
                // CREATING NEW POST
                $new_post = array(
                    'post_title'    => $data,
                    'post_status'   => 'publish',
                    'post_type'     => $this->postSlug,
                    'meta_input'    => [
                        'to_code' => $data
                    ]

                );

                // Вставляем новый пост в базу данных
                wp_insert_post($new_post);
            }
        } catch (Exception $e) {
            error_log($e->getMessage());

            return false;
        }

        return true;
    }

    private function getPosts()
    {
        $posts = get_posts(array(
            'numberposts' => -1,
            'post_type' => $this->postSlug
        ));
        return $posts;
    }
}
