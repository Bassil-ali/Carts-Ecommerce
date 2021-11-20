@extends('layouts.home.app')

@section('content')
        <div class="breadcrumb-bar">
			<div class="container">
				<ol class="breadcrumb">
				   <li class="breadcrumb-item"><a href="/"><i class="fa fa-home"></i></a></li>
				   <li class="breadcrumb-item"> @lang('home.support_tickets')</li>
				</ol>
			</div>
		</div>
        <!--breadcrumb-bar-->
        <section class="section_ticit_supp">
            <div class="container">
              @if (session()->has('message'))
              <div class="alert alert-success">
                  <center>{{ session()->get('message') }}</center>
              </div>
          @endif
                <div class="sec_head text-center">
                    <h2>@lang('home.support_tickets')</h2>
                    <p>@lang('home.ticket')</p>
                </div>
                <div class="content-ticit table-responsive">
                    <a class="add-ticit" data-toggle="modal" data-target="#modal-new-ticit"><i class="zmdi zmdi-plus"></i>@lang('home.new_tickets')</a>
                    <table class="table-site table-ticit">
                      <tr>
                        <th>#</th>
                        <th>@lang('home.title_ticket')</th>
                        <th>@lang('home.status_ticket')</th>
                        <th>@lang('home.type_ticket')</th>
                        <th>@lang('home.data_added')</th>
                        <th>@lang('home.latest_update')</th>
                      </tr>
                      @foreach($ticits as $ticit)
                      <tr>
                        <td>{{$ticit->number_ticit}}</td>
                        <td>{{$ticit->ticit_address}}</td>
                        @if($ticit->ticit_answer== 0)
                        <td>not answer</td>
                        @else
                        <td>answered</td>
                        @endif
                        <td>{{$ticit->ticit_type}}</td>
                        <td>{{$ticit->created_at}}</td>
                        <td>{{$ticit->updated_at}}</td>
                      </tr>
                      @endforeach
                     
                    </table>
                </div>
            </div>
        </section> 
        <!--section_ticit_supp-->   
		<div class="modal fade" id="modal-new-ticit" tabindex="-1" role="dialog" aria-labelledby="modal-new-ticit" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <p>@lang('home.new_tickets')</p>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span class="zmdi zmdi-close"></span>
                </button>
              </div>
              <div class="modal-body">
                <form action="{{route('ticit.store')}}" method="post" class="form-new-ticit" id="upload-image-form" enctype="multipart/form-data">
                  {{ csrf_field() }}
                  {{ method_field('post') }}
                    <div class="form-group">
                        <label>@lang('home.title_ticket')</label>
                        <input type="text" id="ticit_address" name="ticit_address" class="form-control" placeholder="@lang('home.add_ticket_title')" required />
                        <span class="text-danger" id="TicitAddressError"></span>
                    </div>
                    <div class="form-group">
                        <label>@lang('home.type_ticket')</label>
                        <select id="ticit_type" name="ticit_type" class="form-control" required>
                              {{-- <option disabled selected id="selected1">@lang('home.qution0')</option> --}}
                              <option value="@lang('home.qution1')">@lang('home.qution1')</option>
                              <option value="@lang('home.qution2')">@lang('home.qution2')</option>
                              <option value="@lang('home.qution3')">@lang('home.qution3')</option>
                              <option value="@lang('home.qution4')">@lang('home.qution4')</option>
                              <option value="@lang('home.qution5')">@lang('home.qution5')</option>
                          </select>
                          <span class="text-danger" id="TicitTypeError"></span>
                    </div>
                    <div class="form-group">
                        <label>@lang('home.attachments')</label>
                        <input type="file" name="images" id="file-7" class="inputfile" data-multiple-caption="{count} arquivos selecionados" required>
                        <label for="file-7"><span class="archive-name"></span><span class="btn-inputfile">@lang('home.browse')</span></label>
                        <span class="text-danger" id="MulteImagesError"></span>
                    </div>
                    <div class="form-group">
                        <label>@lang('home.ticket_details')</label>
                        <textarea name="details_ticit" id="details_ticit" class="form-control" placeholder="@lang('home.add_ticket_details')" required></textarea>
                        <span class="text-danger" id="DetailsTicitError"></span>
                    </div>
                    <div class="form-group">
                        <button class="btn-shop">@lang('dashboard.add')</button>

                    </div>
                </form>
              </div>
            </div>
          </div>
        </div>
	</div>
	<!--main-wrapper--> 
@endsection

@push('loginReagiter')
    
      <script>
        new WOW().init();
        
        
        /*
  By Osvaldas Valutis, www.osvaldas.info
  Available for use under the MIT License
*/

'use strict';

;( function( $, window, document, undefined )
{
  $( '.inputfile' ).each( function()
  {
    var $input   = $( this ),
      $label   = $input.next( 'label' ),
      labelVal = $label.html();

    $input.on( 'change', function( e )
    {
      var fileName = '';

      if( this.files && this.files.length > 1 )
        fileName = ( this.getAttribute( 'data-multiple-caption' ) || '' ).replace( '{count}', this.files.length );
      else if( e.target.value )
        fileName = e.target.value.split( '\\' ).pop();

      if( fileName )
        $label.find( '.archive-name' ).html( fileName );
      else
        $label.html( labelVal );
    });

    // Firefox bug fix
    $input
    .on( 'focus', function(){ $input.addClass( 'has-focus' ); })
    .on( 'blur', function(){ $input.removeClass( 'has-focus' ); });
  });
})( jQuery, window, document );

        
  </script>

@endpush
 