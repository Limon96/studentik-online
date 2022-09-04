<div class="kat_wrap">
    <h2>Категории</h2>
    <div class="list_category_blog">
        @foreach($blogCategories as $blogCategory)
            <div class="item">
                <a href="{{ route('blog_category.index', $blogCategory->slug) }}">
                    <div class="img_svg">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 40"
                             width="40px" height="40px">
                            <path fill="#b6dcfe"
                                  d="M1.5 35.5L1.5 4.5 11.793 4.5 14.793 7.5 35.5 7.5 35.5 35.5z"/>
                            <path fill="#4788c7"
                                  d="M11.586,5l2.707,2.707L14.586,8H15h20v27H2V5H11.586 M12,4H1v32h35V7H15L12,4L12,4z"/>
                            <g>
                                <path fill="#dff0fe"
                                      d="M1.599 35.5L5.417 14.5 16.151 14.5 19.151 12.5 39.41 12.5 35.577 35.5z"/>
                                <path fill="#4788c7"
                                      d="M38.82,13l-3.667,22H2.198l3.636-20H16h0.303l0.252-0.168L19.303,13H38.82 M40,12H19l-3,2H5L1,36 h35L40,12L40,12z"/>
                            </g>
                        </svg>
                    </div>
                    <div class="text">{{ $blogCategory->title }}</div>
                    <div class="count">{{ $blogCategory->getCountPosts($blogCategory) }}</div>
                </a>
            </div>
        @endforeach
    </div>
</div>
