@foreach($posts as $post)
<x-blog-card :post="$post" />
@endforeach
