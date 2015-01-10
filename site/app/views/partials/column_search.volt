<script>
$(document).ready(function() {
  var table = $('#example_search').DataTable({
    serverSide: true,
    ajax: {
      url: '/example_basic',
      method: 'POST'
    },
    columns: [
      {data: 'id', searchable: false},
      {data: 'name'},
      {data: 'email'}
    ]
  });

  table.columns().eq(0).each(function(idx) {
    $('input', table.column(idx).footer()).on('keyup change', function() {
      table.column(idx)
           .search(this.value)
           .draw();
    });
  });
});
</script>
<h4>Search-by-column</h4>
<p>Using with a column filters (it does a "AND" filtering)</p>

<code>Controller</code>
<pre>
$app->post('/example_basic', function() use($app) {
  $builder = new \DataTables\Adapters\QueryBuilder();
  $builder->columns('id, name, email')
          ->from('Example\Models\User');
          
  echo $builder->getResponse()->getContent();
});
</pre>

<code>Javascript</code>
<pre>
$(document).ready(function() {
  var table = $('#example_search').DataTable({
    serverSide: true,
    ajax: {
      url: '/example_basic',
      method: 'POST'
    },
    columns: [
      {data: 'id', searchable: false},
      {data: 'name'},
      {data: 'email'}
    ]
  });

  table.columns().eq(0).each(function(idx) {
    $('input', table.column(idx).footer()).on('keyup change', function() {
      table.column(idx)
           .search(this.value)
           .draw();
    });
  });
});
</pre>
<br>

<table class="table table-striped" id="example_search">
  <thead>
    <tr>
      <th>ID</th>
      <th>Name</th>
      <th>Email</th>
    </tr>
  </thead>
  <tbody>
  </tbody>
  <tfoot>
    <tr>
      <th>&nbsp;</th>
      <th><input type="text" placeholder="Search name"></th>
      <th><input type="text" placeholder="Search email"></th>
    </tr>
  </tfoot>
</table>