<!-- Payment Modal -->
  <div id="paymentModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
    <div class="bg-white text-black rounded-lg p-6 w-full max-w-md shadow-lg relative">
      <div id="paymentContent">
        <h2 class="text-2xl font-bold mb-4 text-center">Payment</h2>
        <form id="paymentForm" enctype="multipart/form-data">
          <label for="paymentMethod">Select Payment Method:</label>
          <select id="paymentMethod" name="payment_method" required class="w-full mb-3 p-2 border rounded">
            <option value="">-- Select --</option>
            <option value="1">GCash</option>
          </select>

          <label for="proofImage" class="block mt-2">Upload Payment Proof:</label>
          <input type="file" id="proofImage" name="proof_image" accept="image/*" required class="w-full mb-3 p-2 border rounded">

          <div id="paymentFields" class="mt-3"></div>
          <p class="mt-4 font-semibold text-xl">Total: ₱<span id="paymentTotal">0</span></p>

          <div class="flex justify-between mt-6">
            <button type="button" id="cancelPayment" class="bg-gray-400 text-white px-4 py-2 rounded">Cancel</button>
            <button type="submit" id="confirmPayment" class="bg-red-600 text-white px-4 py-2 rounded">Confirm Payment</button>
          </div>
        </form>
      </div>


      <div id="paymentSuccess" class="hidden text-center">
        <h2 class="text-2xl font-bold text-black mb-3">Payment Successful!</h2>
        <p class="text-gray-700 mb-6">Your ticket has been confirmed. You can now return to the movie list.</p>

        <div class="flex justify-center gap-4 mt-4">
          <a id="myTicketsLink"
            href="{{ route('tickets.index') }}"
            class="bg-blue-600 text-white px-6 py-2 rounded font-semibold hover:bg-blue-700">
            My Tickets
          </a>

          <button id="returnHome"
            class="bg-[#FFC90D] text-black px-6 py-2 rounded font-semibold hover:bg-yellow-400">
            Return to Movies
          </button>
        </div>
      </div>
    </div>
  </div>