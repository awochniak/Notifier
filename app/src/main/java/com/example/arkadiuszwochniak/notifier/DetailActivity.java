package com.example.arkadiuszwochniak.notifier;

import android.os.Bundle;
import android.os.PersistableBundle;
import android.support.v7.app.AppCompatActivity;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;
import android.widget.Toast;

import org.w3c.dom.Text;

public class DetailActivity extends AppCompatActivity {
    TextView nameTV, statusTV, messageTV;
    String title, status, message;
    Button viewButton;

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.detail_activity);

        String title = getIntent().getStringExtra("title");
        String status = getIntent().getStringExtra("status");
        String message = getIntent().getStringExtra("message");

        message = message.replace("\\n","\n");


        nameTV = findViewById(R.id.name);
        statusTV = findViewById(R.id.status);
        messageTV = findViewById(R.id.message);
        viewButton = findViewById(R.id.viewButton);

        viewButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Toast.makeText(getBaseContext(), "test", Toast.LENGTH_SHORT).show();

            }
        });

        nameTV.setText(title);
        statusTV.setText(status);
        messageTV.setText(message);
    }

}
