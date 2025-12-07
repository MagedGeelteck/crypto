@foreach ($ratings as $item)
    <li class="comment-container d-flex flex-wrap">
        <div class="comment-avatar">
            <img src="{{ getImage(getFilePath('userProfile').'/'.$item->user->image,getFileSize('userProfile'), true) }}" alt="avatar">
        </div>
        <div class="comment-box">
            <div class="ratings-container">
                <div class="product-ratings">
                    @for($i=0; $i < $item->rating; $i++)
                        <i class="las la-star"></i>
                    @endfor
                    @for($i=0; $i < 5-$item->rating; $i++)
                        <i class="lar la-star"></i>
                    @endfor
                </div>
            </div>
            <div class="comment-info mb-1">
                <h4 class="avatar-name">{{$item->user->fullname}}</h4> - <span class="comment-date">{{showDateTime($item->created_at,'F d,Y')}}</span>
            </div>
            <div class="comment-text">
                <p>{{__($item->review)}}</p>
            </div>
        </div>
    </li>
@endforeach
