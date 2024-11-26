<x-app-layout>
    
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Api Tokens
        </h2>
    </x-slot>

    <div id="app">

        <x-container class="py-8">

            {{-- Crear access token --}}

            <x-form-section class="mb-12">
                <x-slot name="title">
                    Access Token
                </x-slot>
                <x-slot name="description">
                    Aqui podra generar un Access Token
                </x-slot>

                <div class="grid grid-cols-6 gap-6">

                    <div class="col-sapn-6 sm:col-span-4">
                        
                        <div v-if="form.errors.length > 0" class="bg-red-100 border border-rey-400 text-red-700 py-3 px-4 rounded">
                            <strong class="font-bold">Whoops! </strong>
                            <span>¡Algo salio mal!</span>
            
                            <ul>
                                <li v-for="error in form.errors">
                                    @{{ error }}
                                </li>
                            </ul>
                        </div>

                        <div>
                            <label for="name">
                                Nombre
                            </label>
                            <input v-model="form.name" type="text" class="w-full mt-1" id="name">
                        </div>

                        <div v-if="scopes.length > 0">

                            <label>Scopes</label>
                            <div v-for="scope in scopes">
                                <label>
                                    <input type="checkbox" name="scopes" :value="scope.id" v-model="form.scopes">
                                    @{{ scope.id }}
                                </label>
                            </div>

                        </div>
                        
                    </div>
                </div>

                <x-slot name="actions">
                    <x-primary-button v-on:click="store()">
                        Crear
                    </x-primary-button>
                </x-slot>
            </x-form-section>

            {{-- Mostrar access tokens --}}

            <x-form-section v-if="tokens.length > 0">
                <x-slot name="title">
                    Lista de Access Tokens
                </x-slot>
                <x-slot name="description">
                    Aqui podras encontrar la lista de Access Tokens creados
                </x-slot>
                
                <div >
                    <table class="text-gray-600">
                        <thead class="border-b border-gray-300">
                            <tr class="text-left">
                                <th class="py-2 w-full">Detalles</th>
                                <th class="py-2">Acción</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-300">
                            <tr v-for="token in tokens">
                                <td class="py-2">
                                    <p><b>@{{ token.name }}</b> - @{{ token.scopes }}</p>
                                </td>
                                <td class="flex divide-x divide-gray-300 py-2">
                                    <a class="pl-2 hover:text-red-600 font-semibold cursor-pointer"
                                    v-on:click="revoke(token)">
                                        Eliminar
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
        
            </x-form-section>

        </x-container>

        {{-- Modal show --}}

        <x-dialog-modal modal="showToken.open"> {{-- el modal se abre cuando showToken.open sea igual a true --}}
            <x-slot name="title">
                Mostrar Access Token
            </x-slot>
    
            <x-slot name="content">
                <div class="space-y-6 overflow-auto">

                        <p class="bg-blue-200 border-solid border-2 border-blue-500 rounded p-3">Copia y guarda este access token en un lugar seguro. ¡No podrás volver a verlo!</p>
                        <span class="font-semibold">Access Token: </span>
                        <span v-text="showToken.token"></span>
                
                </div>
            </x-slot>
    
            <x-slot name="footer">
                <button v-on:click="showToken.open = false"
                type="button" class="inline-flex w-full justify-center rounded-md bg-gray-700 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-gray-500 sm:ml-3 sm:w-auto disabled:opacity-50">Listo</button>
            </x-slot>
        </x-dialog-modal>

    </div>

    @push('js')

        <script>

            const { createApp } = Vue

            createApp({
                
                data() {

                    return {
                        form:{
                            name: null,
                            errors: [],
                            scopes: [],
                        },
                        tokens: [],
                        showToken:{
                            open: false,
                            token: null,
                        },
                        scopes: [],
                    }
                },

                mounted() {
                    this.getTokens();
                    this.getScopes();
                },

                methods:{
                    store: function () {
                        axios.post('/oauth/personal-access-tokens', this.form)
                            .then(response => {
                                this.form.name = null;
                                this.form.errors = [];
                                this.form.scopes = [];

                                this.showToken.token = response.data.accessToken;
                                this.showToken.open = true;

                                this.getTokens();
                            })
                            .catch(error => {

                                this.form.errors = Object.values(error.response.data.errors).flat();

                            })
                    },

                    getTokens: function () {
                        axios.get('/oauth/personal-access-tokens')
                        .then(response => {
                            this.tokens = response.data;
                        })
                    },

                    show: function (token) {
                        this.showToken.open = true;
                        this.showToken.id = token.id;
                    },

                    revoke: function (token) {
                        Swal.fire({
                            title: "Are you sure?",
                            text: "You won't be able to revert this!",
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#3085d6",
                            cancelButtonColor: "#d33",
                            confirmButtonText: "Yes, delete it!"
                            }).then((result) => {
                            if (result.isConfirmed) {

                                axios.delete('/oauth/personal-access-tokens/' + token.id)

                                .then(response => {
                                    this.getTokens();  
                                })

                                Swal.fire({
                                title: "Deleted!",
                                text: "Your file has been deleted.",
                                icon: "success"
                                });
                            }
                            });
                    },

                    getScopes: function () {
                        axios.get('/oauth/scopes')
                        .then(response => {
                            this.scopes = response.data;
                        })
                    }
                }
            }).mount('#app')
                        
        </script>

    @endpush

</x-app-layout>