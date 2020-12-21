@extends('indigo-layout::main')

@section('meta_title', _p('prestashop::pages.admin.setting.meta_title', 'Settings') . ' - ' . config('app.name'))
@section('meta_description', _p('prestashop::pages.admin.setting.meta_description', 'Settings in application'))

@push('head')

@endpush

@section('title')
    {{ _p('prestashop::pages.admin.setting.headline', 'Settings') }}
@endsection

@section('create_button')

 @endsection

@section('content')
    <div class="grid">
        <div class="cell-1-1 cell--dsm">
            <h4>{{ _p('prestashop::pages.admin.setting.settings', 'Setting') }}</h4>
            <div class="card">
                <div class="card-body">
                    <content-wrapper :url="$url.urlFromOnlyQuery('{{ route('prestashop.admin.setting.scope')}}', ['page', 'limit'], $route.query)"
                        :check-empty="function(test) { return !(test && (test.data && test.data.length || test.length)) }"
                        name="settings_table">
                        <template slot-scope="table">
                            <table-builder :default="table.data">
                                <tb-column name="key" label="{{ _p('prestashop::pages.admin.setting.key', 'Key') }}"></tb-column>
                                <tb-column name="value" label="{{ _p('prestashop::pages.admin.setting.value', 'Value') }}"></tb-column>
                                <tb-column name="manage" label="{{ _p('prestashop::pages.admin.setting.options', 'Options')  }}">
                                    <template slot-scope="col">
                                        <context-menu right boundary="table">
                                            <button type="submit" slot="toggler" class="btn">
                                                {{_p('prestashop::pages.admin.setting.options', 'Options')}}
                                            </button>
                                            <cm-button @click="AWEMA._store.commit('setData', {param: 'editSetting', data: col.data}); AWEMA.emit('modal::edit_setting:open')">
                                                {{_p('prestashop::pages.admin.setting.edit', 'Edit')}}
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

    <modal-window name="edit_setting" class="modal_formbuilder" title="{{ _p('prestashop::pages.admin.setting.edit_setting', 'Edit setting') }}">
        <form-builder url="{{ route('prestashop.admin.setting.update') }}/{id}" method="patch"
                      @sended="AWEMA.emit('content::settings_table:update')"
                      send-text="{{ _p('prestashop::pages.admin.setting.save', 'Save') }}" store-data="editSetting">
            <div class="grid" v-if="AWEMA._store.state.editSetting">
                <div class="cell">
                    <fb-input name="key" label="{{ _p('prestashop::pages.admin.setting.key', 'Key') }}"></fb-input>
                    <fb-input name="value" label="{{ _p('prestashop::pages.admin.setting.value', 'Value') }}"></fb-input>
                </div>
            </div>
        </form-builder>
    </modal-window>
@endsection
