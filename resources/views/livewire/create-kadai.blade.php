<div class="fixed z-10 inset-0 overflow-y-auto">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0 ease-out duration-300">
        <div class="fixed inset-0 tansition-opacity">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
            <form>
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <!--Title-->
                    <div class="flex justify-between items-center pb-3">
                        <p class="text-2xl font-bold"></p>
                        <div class="cursor-pointer z-50" wire:click="closeModal()">
                            <svg class="fill-current text-black" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18">
                            <path d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="name" class="block text-gray-700 text-sm font-bold mb-2">課題名</label>
                        <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="name" placeholder="課題名" wire:model="name">
                        @error('name') <span class="text-red-500">{{ $message }}</span>@enderror       
                    </div>
                    @if(!$kadai_id)
                    <div class="mb-4">
                        <span class="block text-gray-700 text-sm font-bold mb-2">対象学年</span>
                        <label class="inline-flex items-center">
                            <input type="radio" class="form-radio" name="target" id="target1" value="1" wire:model="target">
                            <span class="ml-2">1年生</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" class="form-radio" name="target" id="target2" value="2" wire:model="target">
                            <span class="ml-2">2年生</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" class="form-radio" name="target" id="target3" value="3" wire:model="target">
                            <span class="ml-2">3年生</span>
                        </label>
                        @error('target') <span class="text-red-500">{{ $message }}</span>@enderror       
                    </div>
                    @endif
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                        <button wire:click.prevent="storeKadai()" type="button" class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-green-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-green-500 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                            保存
                        </button>
                    </span>
                    <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                        <button wire:click="closeModal()" type="button" class="inline-flex justify-center w-full rounded-md border border-gray-500 px-4 py-2 bg-white text-base leading-6 font-medium text-gray-500 shadow-sm hover:bg-gray-300 focus:outline-none focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                            キャンセル
                        </button>
                    </span>
                </div>
            </form>
        </div>
    </div>
</div>