<?php

if (! function_exists('remove_tags_whitespace')) {
    function remove_tags_whitespace($tagItem)
    {
        $tags = explode(',', trim($tagItem));
        $arr = [];
        foreach ($tags as $tag) {
            array_push($arr, trim($tag));
        }

        return $arr;
    }
}

if (! function_exists('cache_duration')){
  function cache_duration()
  {
      return now()->addMinute(10);
  }
}
