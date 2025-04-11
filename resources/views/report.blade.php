<html>
<body>
<h1>Report</h1>

@foreach ($items as $item)
    <p>{{ $item->title }} - {{ $item->description }}</p>
    <div style="page-break-after: always;"></div>
@endforeach


<script type="text/php">
    if (isset($pdf)) {
        $pdf->page_script(function ($pageNumber, $pageCount, $pdf) {
            $pdf->text(500, 820, "Page $pageNumber of $pageCount", null, 10);
        });
    }
</script>
</body>
</html>
