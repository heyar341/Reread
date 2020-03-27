@extends('layouts.search_book')

@section('content')
    <div class="container">
        <div class="row d-flex">
            @foreach($books as $book)
                <div class="col-lg-6">
                    <div class="card mb-3" style="max-width: 540px; min-height:250px;">
                        <div class="row" style="min-height: 190px">
                            {{--書籍情報の左側--}}
                            <div class="col-sm-4 mt-2">
                                <img
                                    src=@if(!empty($book->volumeInfo->imageLinks->thumbnail))"{{ $book->volumeInfo->imageLinks->thumbnail }}"@else
                                    "https://reread-uploads.s3-ap-northeast-1.amazonaws.com/default-image/no_image_avairable.png"@endif
                                class="w-80 d-block ml-2"
                                style="display: inline-block;max-height: 180px">
                            </div>
                            {{--書籍情報の右側--}}
                            <div class="col-sm-8 mt-2">
                                <h5>@if(mb_strlen($book->volumeInfo->title) > 30){{ mb_substr($book->volumeInfo->title,0,30) }}
                                    ･･･@else {{ $book->volumeInfo->title }}@endif</h5>
                                <a href="{{$book->volumeInfo->infoLink}}">この書籍の詳細情報</a><br>
                                <small>著者：@if(empty($book->volumeInfo->authors))
                                        不明@else{{ implode(',',$book->volumeInfo->authors) }}@endif
                                    、</small>
                                <small>出版年：@if(empty($book->volumeInfo->publishedDate))
                                        不明@else{{ mb_substr($book->volumeInfo->publishedDate,0,4) }}、@endif</small>
                                <small>ページ数：@if(empty($book->volumeInfo->pageCount))
                                        不明@else{{ $book->volumeInfo->pageCount}}@endif
                                </small>
                                <p>@if(!empty($book->volumeInfo->description)){{ mb_substr($book->volumeInfo->description,0,50) }}@endif
                                    @if(mb_strlen($book->volumeInfo->description) > 50 || empty($book->volumeInfo->description) )･･･@endif</p>
                            </div>
                        </div>
                        {{--書籍情報を投稿作成画面に渡す--}}
                        <div class="d-flex mb-2">
                            <form class="mx-auto" action="/post/create" method="post">
                                @csrf
                                {{--book->idは主キーとして使えないので、bookCodeとして保存--}}
                                <input type="hidden" name="bookCode" value="{{ $book->id }}">
                                <input type="hidden" name="thumbnail"
                                       value=@if(!empty($book->volumeInfo->imageLinks->thumbnail))"{{ $book->volumeInfo->imageLinks->thumbnail }}"@else
                                    "https://reread-uploads.s3-ap-northeast-1.amazonaws.com/default-image/no_image_avairable.png"@endif
                                >
                                <input type="hidden" name="title" value="{{ $book->volumeInfo->title }}">
                                <input type="hidden" name="infoLink" value="{{ $book->volumeInfo->infoLink }}">
                                <input type="hidden" name="authors"
                                       value="@if(empty($book->volumeInfo->authors))不明@else{{ implode(',',$book->volumeInfo->authors) }}@endif">
                                <input type="hidden" name="publishedDate"
                                       value="@if(empty($book->volumeInfo->publishedDate))不明@else{{ $book->volumeInfo->publishedDate }}@endif">
                                <input type="hidden" name="pageCount"
                                       value="@if(empty($book->volumeInfo->pageCount))不明@else{{ $book->volumeInfo->pageCount}}@endif">
                                <input type="hidden" name="description"
                                       value="@if(empty($book->volumeInfo->description))･･･@else{{ mb_substr($book->volumeInfo->description,0,100) }}@endif">
                                <div>
                                    <button class="btn text-white" style="background-color: #2C7CFF;">要約を投稿する
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="row book-search">
            <div class="col-12">
                <h3 class="mt-5 mb-3">見つからない場合、より詳しく書籍の題名をご入力ください</h3>
                <form class="mb-5" action="search" method="post">
                    @csrf
                    <div class="d-flex">
                        <div>
                            <input class="py-1 @error('bookName') is-invalid @enderror" type="text" name="bookName"
                                   value="{{$book_name_base}}"
                                   placeholder="書籍名を入力" style="width: 250px">
                            @error('bookName')
                            <span class="invalid-feedback" role="alert">
<strong>{{ $message }}</strong>
</span>
                            @enderror
                        </div>
                        <div>
                            <button class="btn btn-primary">本を検索</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
