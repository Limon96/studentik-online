@php if (!isset($prefix)) { $prefix = ''; } @endphp
<tr>
    <td>{{ $category_item->id }}</td>
    <td>{!! $prefix !!} {{ $category_item->title }}</td>
    <td>@if($category_item->status) Опубликован @else Черновик @endif</td>
    <td>{{ $category_item->created_at }}</td>
    <td>{{ $category_item->updated_at }}</td>
    <td>
        <a href="{{ route('blog_category.index', $category_item->slug) }}" class="btn btn-sm btn-info" target="_blank"><i class="fa fa-eye"></i></a>
        <a href="{{ route('admin.blog_category.edit', $category_item->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-pencil"></i></a>
        <form method="post" class="form-delete"
              action="{{ route('admin.blog_category.destroy', $category_item->id) }}">
            @method('DELETE')
            @csrf
            <button type="submit"
                    class="btn btn-sm btn-danger btn-delete point_c" onclick="return confirm('Действительно хотите удалить запись?');"><i class="fa fa-trash"></i></button>
        </form>
    </td>
</tr>

@foreach($category_item->categories as $category)
    @include('blog_category.admin.category_item', [
        'category_item' => $category,
        'prefix' => "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $prefix
    ])
@endforeach
