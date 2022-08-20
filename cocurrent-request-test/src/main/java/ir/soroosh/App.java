package ir.soroosh;

import com.github.javafaker.Faker;

import java.io.IOException;
import java.util.Locale;

/**
 * Hello world!
 */
public class App {
    public static void main(String[] args) {
        final Faker faker = new Faker(new Locale("en-US"));
        Thread t1 = new Thread(() -> {
            int i = 0;
            while (true) {
                ChargeWalletService chargeWalletService = new ChargeWalletService("http://localhost:8000/api/v1");
                try {
                    if (!chargeWalletService.charge("+989"+faker.numerify("#########"), "73831971")) {
                        break;
                    }
                    i++;
                } catch (IOException e) {
                    e.printStackTrace();
                }
            }
            System.out.println(i);
        });
        Thread t2 = new Thread(() -> {
            int i = 0;
            while (true) {
                ChargeWalletService chargeWalletService = new ChargeWalletService("http://localhost:8000/api/v1");
                try {
                    if (!chargeWalletService.charge("+989"+faker.numerify("#########"), "73831971")) {
                        break;
                    }
                    i++;
                } catch (IOException e) {
                    e.printStackTrace();
                }
            }
            System.out.println(i);
        });
        t1.start();
        t2.start();
    }
}
