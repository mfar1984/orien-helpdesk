<!-- Bad Word Modal -->
<div id="bad-word-modal" class="fixed inset-0 flex items-center justify-center" style="background-color: rgba(0,0,0,0.5) !important; z-index: 9999 !important; display: none;">
    <div style="background-color: #ffffff !important; border-radius: 12px !important; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25) !important; width: 100% !important; max-width: 520px !important; margin: 16px !important; overflow: hidden !important;">
        <div style="padding: 16px 20px !important; border-bottom: 1px solid #e5e7eb !important; display: flex !important; align-items: center !important; justify-content: space-between !important; background-color: #fef3c7 !important;">
            <div style="display: flex !important; align-items: center !important; gap: 10px !important;">
                <div style="width: 36px !important; height: 36px !important; border-radius: 8px !important; background-color: #f59e0b !important; display: flex !important; align-items: center !important; justify-content: center !important;">
                    <span class="material-symbols-outlined" style="font-size: 20px !important; color: #ffffff !important;">warning</span>
                </div>
                <h3 id="bad-word-modal-title" style="font-size: 14px !important; font-weight: 600 !important; color: #92400e !important; font-family: Poppins, sans-serif !important; margin: 0 !important;">Add Bad Word</h3>
            </div>
            <button type="button" onclick="closeModal('bad-word-modal')" style="width: 32px !important; height: 32px !important; border-radius: 6px !important; border: none !important; background-color: transparent !important; cursor: pointer !important; display: flex !important; align-items: center !important; justify-content: center !important;" onmouseover="this.style.backgroundColor='#e5e7eb'" onmouseout="this.style.backgroundColor='transparent'">
                <span class="material-symbols-outlined" style="font-size: 20px !important; color: #6b7280 !important;">close</span>
            </button>
        </div>
        <!-- Modal Tabs -->
        <div style="display: flex !important; border-bottom: 1px solid #e5e7eb !important;">
            <button type="button" onclick="switchModalTab('bad-word', 'single')" id="bad-word-tab-single" style="flex: 1 !important; padding: 12px !important; font-size: 12px !important; font-weight: 500 !important; color: #3b82f6 !important; background-color: #ffffff !important; border: none !important; border-bottom: 2px solid #3b82f6 !important; cursor: pointer !important; font-family: Poppins, sans-serif !important;">
                <span class="material-symbols-outlined" style="font-size: 16px !important; vertical-align: middle !important; margin-right: 4px !important;">person</span>
                Single
            </button>
            <button type="button" onclick="switchModalTab('bad-word', 'bulk')" id="bad-word-tab-bulk" style="flex: 1 !important; padding: 12px !important; font-size: 12px !important; font-weight: 500 !important; color: #6b7280 !important; background-color: #f9fafb !important; border: none !important; border-bottom: 2px solid transparent !important; cursor: pointer !important; font-family: Poppins, sans-serif !important;">
                <span class="material-symbols-outlined" style="font-size: 16px !important; vertical-align: middle !important; margin-right: 4px !important;">group_add</span>
                Bulk Import
            </button>
        </div>
        <!-- Single Form -->
        <form id="bad-word-form" action="{{ route('tools.bad-word.store') }}" method="POST">
            @csrf
            <div id="bad-word-single" style="padding: 20px !important;">
                <div style="display: flex !important; flex-direction: column !important; gap: 16px !important;">
                    <div>
                        <label style="display: block !important; font-size: 11px !important; font-weight: 500 !important; color: #374151 !important; margin-bottom: 6px !important; font-family: Poppins, sans-serif !important;">
                            Word <span style="color: #ef4444 !important;">*</span>
                        </label>
                        <input type="text" name="word" required
                               style="width: 100% !important; padding: 10px 12px !important; border: 1px solid #d1d5db !important; border-radius: 6px !important; font-family: Poppins, sans-serif !important; font-size: 12px !important; color: #1f2937 !important; outline: none !important; box-sizing: border-box !important;"
                               placeholder="Enter bad word"
                               onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#d1d5db'">
                    </div>
                    <div>
                        <label style="display: block !important; font-size: 11px !important; font-weight: 500 !important; color: #374151 !important; margin-bottom: 6px !important; font-family: Poppins, sans-serif !important;">
                            Severity <span style="color: #ef4444 !important;">*</span>
                        </label>
                        <select name="severity" required
                                style="width: 100% !important; padding: 10px 12px !important; border: 1px solid #d1d5db !important; border-radius: 6px !important; font-family: Poppins, sans-serif !important; font-size: 12px !important; color: #1f2937 !important; outline: none !important; box-sizing: border-box !important; background-color: #ffffff !important;">
                            <option value="low">Low</option>
                            <option value="medium" selected>Medium</option>
                            <option value="high">High</option>
                        </select>
                    </div>
                    <div>
                        <label style="display: block !important; font-size: 11px !important; font-weight: 500 !important; color: #374151 !important; margin-bottom: 6px !important; font-family: Poppins, sans-serif !important;">
                            Reason (Optional)
                        </label>
                        <textarea name="reason" rows="3"
                                  style="width: 100% !important; padding: 10px 12px !important; border: 1px solid #d1d5db !important; border-radius: 6px !important; font-family: Poppins, sans-serif !important; font-size: 12px !important; color: #1f2937 !important; resize: vertical !important; outline: none !important; box-sizing: border-box !important;"
                                  placeholder="Why is this word prohibited?"
                                  onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#d1d5db'"></textarea>
                    </div>
                </div>
            </div>
            <div style="padding: 16px 20px !important; border-top: 1px solid #e5e7eb !important; display: flex !important; justify-content: flex-end !important; gap: 10px !important; background-color: #f9fafb !important;" id="bad-word-single-footer">
                <button type="button" onclick="closeModal('bad-word-modal')" 
                        style="padding: 10px 20px !important; font-size: 12px !important; font-weight: 500 !important; color: #374151 !important; background-color: #ffffff !important; border: 1px solid #d1d5db !important; border-radius: 6px !important; cursor: pointer !important; font-family: Poppins, sans-serif !important;"
                        onmouseover="this.style.backgroundColor='#f3f4f6'" onmouseout="this.style.backgroundColor='#ffffff'">
                    Cancel
                </button>
                <button type="submit"
                        style="padding: 10px 20px !important; font-size: 12px !important; font-weight: 500 !important; color: #ffffff !important; background-color: #f59e0b !important; border: none !important; border-radius: 6px !important; cursor: pointer !important; font-family: Poppins, sans-serif !important; display: flex !important; align-items: center !important; gap: 6px !important;"
                        onmouseover="this.style.backgroundColor='#d97706'" onmouseout="this.style.backgroundColor='#f59e0b'">
                    <span class="material-symbols-outlined" style="font-size: 16px !important;">warning</span>
                    Add Word
                </button>
            </div>
        </form>
        <!-- Bulk Form -->
        <form id="bad-word-bulk-form" action="{{ route('tools.bad-word.bulk') }}" method="POST" style="display: none;">
            @csrf
            <div id="bad-word-bulk" style="padding: 20px !important;">
                <div style="display: flex !important; flex-direction: column !important; gap: 16px !important;">
                    <div style="padding: 12px !important; background-color: #fef3c7 !important; border: 1px solid #fcd34d !important; border-radius: 6px !important;">
                        <p style="font-size: 11px !important; color: #92400e !important; margin: 0 !important; font-family: Poppins, sans-serif !important;">
                            <span class="material-symbols-outlined" style="font-size: 14px !important; vertical-align: middle !important; margin-right: 4px !important;">info</span>
                            Enter one word per line. All words will use the same severity and reason.
                        </p>
                    </div>
                    <div>
                        <label style="display: block !important; font-size: 11px !important; font-weight: 500 !important; color: #374151 !important; margin-bottom: 6px !important; font-family: Poppins, sans-serif !important;">
                            Words <span style="color: #ef4444 !important;">*</span>
                        </label>
                        <textarea name="words" required rows="8"
                                  style="width: 100% !important; padding: 10px 12px !important; border: 1px solid #d1d5db !important; border-radius: 6px !important; font-family: monospace !important; font-size: 12px !important; color: #1f2937 !important; resize: vertical !important; outline: none !important; box-sizing: border-box !important;"
                                  placeholder="word1&#10;word2&#10;word3"
                                  onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#d1d5db'"></textarea>
                    </div>
                    <div>
                        <label style="display: block !important; font-size: 11px !important; font-weight: 500 !important; color: #374151 !important; margin-bottom: 6px !important; font-family: Poppins, sans-serif !important;">
                            Severity (applies to all) <span style="color: #ef4444 !important;">*</span>
                        </label>
                        <select name="severity" required
                                style="width: 100% !important; padding: 10px 12px !important; border: 1px solid #d1d5db !important; border-radius: 6px !important; font-family: Poppins, sans-serif !important; font-size: 12px !important; color: #1f2937 !important; outline: none !important; box-sizing: border-box !important; background-color: #ffffff !important;">
                            <option value="low">Low</option>
                            <option value="medium" selected>Medium</option>
                            <option value="high">High</option>
                        </select>
                    </div>
                    <div>
                        <label style="display: block !important; font-size: 11px !important; font-weight: 500 !important; color: #374151 !important; margin-bottom: 6px !important; font-family: Poppins, sans-serif !important;">
                            Reason (applies to all, optional)
                        </label>
                        <input type="text" name="reason"
                               style="width: 100% !important; padding: 10px 12px !important; border: 1px solid #d1d5db !important; border-radius: 6px !important; font-family: Poppins, sans-serif !important; font-size: 12px !important; color: #1f2937 !important; outline: none !important; box-sizing: border-box !important;"
                               placeholder="Bulk import - profanity filter"
                               onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#d1d5db'">
                    </div>
                </div>
            </div>
            <div style="padding: 16px 20px !important; border-top: 1px solid #e5e7eb !important; display: flex !important; justify-content: flex-end !important; gap: 10px !important; background-color: #f9fafb !important;" id="bad-word-bulk-footer">
                <button type="button" onclick="closeModal('bad-word-modal')" 
                        style="padding: 10px 20px !important; font-size: 12px !important; font-weight: 500 !important; color: #374151 !important; background-color: #ffffff !important; border: 1px solid #d1d5db !important; border-radius: 6px !important; cursor: pointer !important; font-family: Poppins, sans-serif !important;"
                        onmouseover="this.style.backgroundColor='#f3f4f6'" onmouseout="this.style.backgroundColor='#ffffff'">
                    Cancel
                </button>
                <button type="submit"
                        style="padding: 10px 20px !important; font-size: 12px !important; font-weight: 500 !important; color: #ffffff !important; background-color: #f59e0b !important; border: none !important; border-radius: 6px !important; cursor: pointer !important; font-family: Poppins, sans-serif !important; display: flex !important; align-items: center !important; gap: 6px !important;"
                        onmouseover="this.style.backgroundColor='#d97706'" onmouseout="this.style.backgroundColor='#f59e0b'">
                    <span class="material-symbols-outlined" style="font-size: 16px !important;">group_add</span>
                    Add All Words
                </button>
            </div>
        </form>
    </div>
</div>
