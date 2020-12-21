@extends('indigo-layout::main')

@section('meta_title', _p('prestashop::pages.user.shop.meta_title', 'Shops') . ' - ' . config('app.name'))
@section('meta_description', _p('prestashop::pages.user.shop.meta_description', 'Shops in shop'))

@push('head')

@endpush

@section('title')
    {{ _p('prestashop::pages.user.shop.headline', 'Shops') }}
@endsection

@section('create_button')
    <button class="frame__header-add" @click="AWEMA.emit('modal::connect:open')" title="{{ _p('prestashop::pages.user.shop.connect_shop', 'Connect shop') }}"><i class="icon icon-plus"></i></button>
@endsection

@section('content')
    <div class="grid">
        <div class="cell-1-1 cell--dsm">
            <h4>{{ _p('prestashop::pages.user.shop.shops', 'Shop') }}</h4>
            <div class="card">
                <div class="card-body">
                    <content-wrapper :url="$url.urlFromOnlyQuery('{{ route('prestashop.user.shop.scope')}}', ['page', 'limit'], $route.query)"
                        :check-empty="function(test) { return !(test && (test.data && test.data.length || test.length)) }"
                        name="shops_table">
                        <template slot-scope="table">
                            <table-builder :default="table.data">
                                <tb-column name="name" label="{{ _p('prestashop::pages.user.shop.name', 'Name') }}"></tb-column>
                                <tb-column name="url" label="{{ _p('prestashop::pages.user.shop.url', 'Website address') }}"></tb-column>
                                <tb-column name="shop_language_name" label="{{ _p('prestashop::pages.user.shop.language', 'Language') }}"></tb-column>
                                <tb-column name="created_at" label="{{ _p('prestashop::pages.user.shop.connected_at', 'Connected at') }}"></tb-column>
                                <tb-column name="manage" label="{{ _p('prestashop::pages.user.shop.options', 'Options')  }}">
                                    <template slot-scope="col">
                                        <context-menu right boundary="table">
                                            <button type="submit" slot="toggler" class="btn">
                                                {{_p('prestashop::pages.user.shop.options', 'Options')}}
                                            </button>
                                            <cm-button @click="AWEMA.ajax({}, '{{route('prestashop.user.shop.check_connection') }}/' + col.data.id, 'get')">
                                                {{_p('prestashop::pages.user.shop.check_connection', 'Check connection')}}
                                            </cm-button>
                                            <cm-button @click="AWEMA._store.commit('setData', {param: 'editShop', data: col.data}); AWEMA.emit('modal::edit_shop:open')">
                                                {{_p('prestashop::pages.user.shop.edit', 'Edit')}}
                                            </cm-button>
                                            <cm-button @click="AWEMA._store.commit('setData', {param: 'deleteShop', data: col.data}); AWEMA.emit('modal::delete_shop:open')">
                                                {{_p('prestashop::pages.user.shop.delete', 'Delete')}}
                                            </cm-button>
                                        </context-menu>
                                    </template>
                                </tb-column>
                            </table-builder>

                            <paginate-builder v-if="table.data"
                                :meta="table.meta"
                            ></paginate-builder>
                        </template>
                        @include('indigo-layout::components.base.loading')
                        @include('indigo-layout::components.base.empty')
                        @include('indigo-layout::components.base.error')
                    </content-wrapper>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modals')

    <modal-window name="connect" class="modal_formbuilder" title="{{ _p('prestashop::pages.user.shop.connect_shop', 'Connect shop') }}">
        <form-builder name="connect" url="{{ route('prestashop.user.shop.store') }}" send-text="{{ _p('prestashop::pages.user.shop.connect', 'Connect') }}"
                      @sended="AWEMA.emit('content::shops_table:update')">
             <div v-if="AWEMA._store.state.forms['connect']">
                 <fb-input name="name" label="{{ _p('prestashop::pages.user.shop.name', 'Name') }}"></fb-input>
                 <fb-input name="url" label="{{ _p('prestashop::pages.user.shop.url', 'Website address') }}"></fb-input>
                 <fb-input name="api_key" label="{{ _p('prestashop::pages.user.shop.api_key', 'API key') }}"></fb-input>
                 <small class="cl-caption">
                     {!! _p('prestashop::pages.user.shop.generate_api_key', 'Please generate an API key in the PrestaShop admin panel "Advanced Parameters > Webservice".') !!}
                 </small>
                 <div class="mt-10">
                     <fb-select name="shop_language_id" :multiple="false" open-fetch options-value="id" options-name="name"
                                :url="'{{ route('prestashop.user.shop.select_language') }}?url=' + AWEMA._store.state.forms['connect'].fields.url + '&api_key=' + AWEMA._store.state.forms['connect'].fields.api_key"
                                :disabled="!AWEMA._store.state.forms['connect'] || !AWEMA._store.state.forms['connect'].fields.url || !AWEMA._store.state.forms['connect'].fields.api_key"
                                placeholder-text=" " label="{{ _p('prestashop::pages.user.shop.language', 'Language') }}">
                     </fb-select>
                     <small class="cl-caption" v-if="!AWEMA._store.state.forms['connect'] || !AWEMA._store.state.forms['connect'].fields.url || !AWEMA._store.state.forms['connect'].fields.api_key">
                         {!! _p('prestashop::pages.user.shop.please_fill_url_api_key_to_select_local_Language', 'Please complete the website address and API key to select the local language.') !!}
                     </small>
                 </div>
            </div>
        </form-builder>
    </modal-window>

    <modal-window name="edit_shop" class="modal_formbuilder" title="{{ _p('prestashop::pages.user.shop.edit_shop', 'Edit shop') }}">
        <form-builder name="edit_shop" url="{{ route('prestashop.user.shop.update') }}/{id}" method="patch"
                      @sended="AWEMA.emit('content::shops_table:update')"
                      send-text="{{ _p('prestashop::pages.user.shop.save', 'Save') }}" store-data="editShop">
            <div v-if="AWEMA._store.state.forms['edit_shop']">
                <fb-input name="name" label="{{ _p('prestashop::pages.user.shop.name', 'Name') }}"></fb-input>
                <fb-input name="url" label="{{ _p('prestashop::pages.user.shop.url', 'Website address') }}"></fb-input>
                <fb-input name="api_key" label="{{ _p('prestashop::pages.user.shop.api_key', 'API key') }}"></fb-input>
                <div class="mt-10">
                    <fb-select name="shop_language_id" :multiple="false" open-fetch auto-fetch options-value="id" options-name="name"
                               :url="'{{ route('prestashop.user.shop.select_language') }}?url=' + AWEMA._store.state.forms['edit_shop'].fields.url + '&api_key=' + AWEMA._store.state.forms['edit_shop'].fields.api_key"
                               :disabled="!AWEMA._store.state.forms['edit_shop'] || !AWEMA._store.state.forms['edit_shop'].fields.url || !AWEMA._store.state.forms['edit_shop'].fields.api_key"
                               placeholder-text=" " label="{{ _p('prestashop::pages.user.shop.language', 'Language') }}"
                               :auto-fetch-value="AWEMA._store.state.editShop.shop_language_id">
                    </fb-select>
                    <small class="cl-caption" v-if="!AWEMA._store.state.forms['edit_shop'] || !AWEMA._store.state.forms['edit_shop'].fields.url || !AWEMA._store.state.forms['edit_shop'].fields.api_key">
                        {!! _p('prestashop::pages.user.shop.please_fill_url_api_key_to_select_local_Language', 'Please complete the website address and API key to select the local language.') !!}
                    </small>
                </div>
            </div>
        </form-builder>
    </modal-window>

    <modal-window name="delete_shop" class="modal_formbuilder" title="{{  _p('prestashop::pages.user.shop.are_you_sure_delete', 'Are you sure delete?') }}">
        <form-builder :edited="true" url="{{route('prestashop.user.shop.delete') }}/{id}" method="delete"
                      @sended="AWEMA.emit('content::shops_table:update')"
                      send-text="{{ _p('prestashop::pages.user.shop.confirm', 'Confirm') }}" store-data="deleteShop"
                      disabled-dialog>

        </form-builder>
    </modal-window>
@endsection
