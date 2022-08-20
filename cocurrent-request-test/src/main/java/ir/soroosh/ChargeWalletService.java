package ir.soroosh;

import com.google.gson.Gson;
import com.google.gson.JsonObject;
import okhttp3.*;

import java.io.IOException;
import java.util.Objects;

public class ChargeWalletService {

    public static final MediaType JSON
        = MediaType.get("application/json; charset=utf-8");
    private final OkHttpClient client = new OkHttpClient();

    private String baseUrl;

    public ChargeWalletService(String baseUrl) {
        this.baseUrl = baseUrl;
    }

    private String post(String url, String json) throws IOException {
        RequestBody body = RequestBody.create(json, JSON);
        Request request = new Request.Builder()
            .url(url)
            .post(body)
            .build();
        try (Response response = client.newCall(request).execute()) {
            return Objects.requireNonNull(response.body()).string();
        }
    }

    public boolean charge(String mobile, String chargeCode) throws IOException {
        JsonObject request = new JsonObject();
        request.addProperty("mobile", mobile);
        request.addProperty("code", chargeCode);
        return (new Gson()).fromJson(this.post(this.baseUrl + "/wallet/charge",request.toString()), JsonObject.class).get("status").getAsBoolean();
    }
}
