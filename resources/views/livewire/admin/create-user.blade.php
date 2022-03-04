<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 text-gray-700">
    <h1 class="text-3xl text-center font-semibold mb-8">Complete los datos para crear un usuario</h1>

    <div class="mb-4">
        <x-jet-label value="Nombre" />
        <x-jet-input type="text"
                     class="w-full"
                     wire:model="name"
                     placeholder="Ingrese el nombre completo del usuario" />

        <x-jet-input-error for="name" />
    </div>

    <div class="grid grid-cols-2 gap-6 mb-4">
        <div>
            <x-jet-label value="Email" />
            <x-jet-input type="email"
                         class="w-full"
                         wire:model="email"
                         placeholder="Ingrese el email del usuario" />

            <x-jet-input-error for="email" />
        </div>
        <div>
            <x-jet-label value="Confirmar email" />
            <x-jet-input type="email"
                         class="w-full"
                         wire:model="email_confirmation"
                         placeholder="Repita el email" />

            <x-jet-input-error for="email_confirmation" />
        </div>
        <div>
            <x-jet-label value="Contraseña" />
            <x-jet-input type="password"
                         class="w-full"
                         wire:model="password"
                         placeholder="Ingrese la contraseña del usuario" />

            <x-jet-input-error for="password" />
        </div>
        <div>
            <x-jet-label value="Confirmar contraseña" />
            <x-jet-input type="password"
                         class="w-full"
                         wire:model="password_confirmation"
                         placeholder="Repita la contraseña" />

            <x-jet-input-error for="password_confirmation" />
        </div>
    </div>

    <div class="mt-4">
        <x-jet-label value="¿Es administrador?" />
        <div class="flex mt-2">
            <label class="mr-6">
                <input wire:model="isAdmin" type="radio" name="status" value="1">
                Sí
            </label>
            <label>
                <input wire:model="isAdmin" type="radio" name="status" value="0">
                No
            </label>
        </div>

        <x-jet-input-error for="isAdmin" />
    </div>

    <div class="flex mt-4">
        <x-jet-button
            wire:loading.attr="disabled"
            wire:target="save"
            wire:click="save"
            class="ml-auto">
            Crear usuario
        </x-jet-button>
    </div>
</div>
