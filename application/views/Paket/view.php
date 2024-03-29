<div class="card shadow mb-4">
  <?=$this->session->flashdata('flash') ?>
    <div class="card-header py-3 d-flex">
      <div>
        <span class="m-0 font-weight-bold text-primary">Data <?=$section ?></span>
      </div>
      <div class="ml-auto">
        <a class="btn btn-sm btn-primary text-light" href="<?=base_url('paket/post')?>"><i class="fa fa-plus"></i> <b>Add Data</b></a>
      </div>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th>Nama Paket</th>
              <th>Tanggal</th>
              <th>Harga</th>
              <th width="450px">Description</th>
              <th width="100px">Action</th>

            </tr>
          </thead>
          <tbody>
          	<?php 
          		foreach($show as $s){ 
              $id = str_replace(['=','+','/'], ['-','_','~'], $this->encryption->encrypt($s->id_paket));

          	 ?>
            
            <tr>
              <td><?=$s->nama_paket ?></td>
              <td><?= date("d-m-Y", strtotime($s->date)) ?></td>
              <td><?=number_format($s->price,0,'','.')?></td>
              <td><?=$s->description_paket?></td>
              <td>
              	<button href="" onclick="deleteConfirm('<?=base_url('paket/delete/'.$id) ?>')" class="btn btn-sm btn-danger" data-target="#modalDelete" data-toggle="modal">Delete</button>
              	<a href="<?=base_url('paket/edit/'.$id) ?>" class="btn btn-sm btn-warning" >Edit</a>
              </td>
            </tr>
            
            <?php 
            } 
            ?>

          </tbody>
        </table>
      </div>
    </div>
</div>

<div class="modal fade" id="modalDelete" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Delete Data</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this data?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
        <a type="button" class="btn btn-danger btn-sm text-light" id="hapus">Delete</a>
      </div>
    </div>
  </div>
</div>

<script>
  function deleteConfirm(url)
  {
  	console.log(url)
    $('#hapus').attr('href',url);
    $('#modalDelete').modal();
  }

</script>