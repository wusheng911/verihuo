<?php
// config
$link_limit = 7; // maximum number of links (a little bit inaccurate, but will be ok for now)
?>

@if ($paginator->lastPage() > 1)
<div class="pagination-centered">
    <ul class="pagination" style="margin-bottom:0;">
        <li class="{{ ($paginator->currentPage() == 1) ? ' arrow unavailable' : '' }}">
            <a href="{{ $paginator->url(1) }}"><i class="fa fa-angle-left fa-lg"></i></a>
        </li>
        @for ($i = 1; $i <= $paginator->lastPage(); $i++)
            <?php
            $half_total_links = floor($link_limit / 2);
            $from = $paginator->currentPage() - $half_total_links;
            $to = $paginator->currentPage() + $half_total_links;
            if ($paginator->currentPage() < $half_total_links) {
               $to += $half_total_links - $paginator->currentPage();
            }
            if ($paginator->lastPage() - $paginator->currentPage() < $half_total_links) {
                $from -= $half_total_links - ($paginator->lastPage() - $paginator->currentPage()) - 1;
            }
            ?>
            @if ($from < $i && $i < $to)
                <li class="{{ ($paginator->currentPage() == $i) ? ' current' : '' }}">
                    <a href="{{ $paginator->url($i) }}">{{ $i }}</a>
                </li>
            @endif
        @endfor
        <li class="{{ ($paginator->currentPage() == $paginator->lastPage()) ? ' arrow unavailable' : '' }}">
            <a href="{{ $paginator->url($paginator->lastPage()) }}"><i class="fa fa-angle-right fa-lg"></i></a>
        </li>
    </ul>
</div>
@endif