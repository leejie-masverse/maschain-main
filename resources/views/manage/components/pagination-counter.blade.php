<?php
/**
 * @var \Illuminate\Pagination\AbstractPaginator $results;
 */
$total = $results->total();
if ($total) {
    $start = ($results->currentPage() - 1) * $results->perPage() + 1;
    $end = $start + $results->count() - 1;
}
else {
    $start = $end = 0;
}
?>
<p class="pagination-counter">
    Records {{ $start }}-{{ $end }} out of {{ $total }} total displayed.
</p>
