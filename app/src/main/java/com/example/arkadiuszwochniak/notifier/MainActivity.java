package com.example.arkadiuszwochniak.notifier;

import android.content.Intent;
import android.os.Build;
import android.support.annotation.NonNull;
import android.support.constraint.ConstraintLayout;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.ProgressBar;
import android.widget.RelativeLayout;
import android.widget.TextView;

import com.firebase.ui.database.FirebaseRecyclerAdapter;
import com.firebase.ui.database.FirebaseRecyclerOptions;
import com.google.android.gms.tasks.OnSuccessListener;
import com.google.firebase.database.DataSnapshot;
import com.google.firebase.database.DatabaseError;
import com.google.firebase.database.DatabaseReference;
import com.google.firebase.database.FirebaseDatabase;
import com.google.firebase.database.ValueEventListener;
import com.google.firebase.iid.FirebaseInstanceId;
import com.google.firebase.iid.InstanceIdResult;

import org.w3c.dom.Text;

import java.lang.invoke.ConstantCallSite;

import javax.security.auth.login.LoginException;

public class MainActivity extends AppCompatActivity {
    RecyclerView recyclerView;
    ProgressBar progressBar;
    ConstraintLayout errorFields;
    ImageView imageView, imageView2;
    TextView errorSite;
    FirebaseDatabase firebaseDatabase;
    DatabaseReference databaseReference;
    FirebaseRecyclerOptions<Post> options;
    FirebaseRecyclerAdapter<Post, MyRecyclerViewHolder> adapter;
    Boolean code;
    String visibility;
    Intent intent;
    String deviceToken;
    String test;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        recyclerView = findViewById(R.id.recycler_view);
        errorSite = findViewById(R.id.errorSite);
        imageView2 = findViewById(R.id.imageView3);
        errorFields = findViewById(R.id.errorFields);
        progressBar = findViewById(R.id.progressBar);
        recyclerView.setLayoutManager(new LinearLayoutManager(this));
        firebaseDatabase = FirebaseDatabase.getInstance();
        databaseReference = firebaseDatabase.getReference("kontrolki");



        String title = getIntent().getStringExtra("title");
        String body = getIntent().getStringExtra("body");

        imageView2.setVisibility(View.GONE);
        errorSite.setText("Trwa pobieranie danych...");

        FirebaseInstanceId.getInstance().getInstanceId().addOnSuccessListener(new OnSuccessListener<InstanceIdResult>() {
            @Override
            public void onSuccess(InstanceIdResult instanceIdResult) {
                deviceToken = instanceIdResult.getToken();
                Log.e("token urządzenie", deviceToken);
                getToken(deviceToken);
            }
        });

        displayContent();

    }

    public void displayContent() {

        options =
                new FirebaseRecyclerOptions.Builder<Post>()
                        .setQuery(databaseReference, Post.class)
                        .build();

        adapter = new FirebaseRecyclerAdapter<Post, MyRecyclerViewHolder>(options) {
            @Override
            protected void onBindViewHolder(@NonNull MyRecyclerViewHolder holder, final int position, @NonNull final Post model) {
                holder.name.setText(model.getTitle());
                code = model.getStatus();
                holder.name.setOnClickListener(new View.OnClickListener() {
                    @Override
                    public void onClick(View v) {
                        intent = new Intent(getApplicationContext(), DetailActivity.class);
                        intent.putExtra("title", model.getTitle());
                        intent.putExtra("status", model.getStatus().toString());
                        intent.putExtra("message", model.getMessage());
                        startActivity(intent);
                    }
                });


                if (code == true) {
                    holder.imageView.setImageResource(android.R.drawable.presence_online);
                } else {
                    holder.imageView.setImageResource(android.R.drawable.presence_busy);
                }
            }


            @NonNull
            @Override
            public MyRecyclerViewHolder onCreateViewHolder(@NonNull ViewGroup viewGroup, int i) {
                View itemView = LayoutInflater.from(getBaseContext()).inflate(R.layout.control_view, viewGroup, false);
                return new MyRecyclerViewHolder(itemView);
            }
        };

        adapter.startListening();
        recyclerView.setAdapter(adapter);

    }

    public void getToken(final String token){

        final FirebaseDatabase database = FirebaseDatabase.getInstance();
        final DatabaseReference ref = database.getInstance().getReference("tokeny");
        ref.addValueEventListener(new ValueEventListener() {
            @Override
             public void onDataChange(DataSnapshot dataSnapshot) {
                for (DataSnapshot dataSnapshot1: dataSnapshot.getChildren()) {
                    if (dataSnapshot.child(token) != null){
                        try {
                            String status = dataSnapshot.child(token).child("status").getValue().toString();
                            String test = dataSnapshot.child(token).toString();
                            Log.e("testowanie", test);
                            checkToken(status);

                        } catch (Exception e) {
                            ref.child(deviceToken).child("status").setValue(false);
                            ref.child(deviceToken).child("token").setValue(deviceToken);
                            ref.child(deviceToken).child("manufacturer").setValue(Build.MANUFACTURER);
                            ref.child(deviceToken).child("model").setValue(Build.MODEL);
                        }
                    }
                }
            }


             @Override
             public void onCancelled(@NonNull DatabaseError databaseError) {
             }

        });

    }

    public void checkToken(String status){
        if(status=="true"){
            Log.e("Komunikat checkToken", "Token został znaleziony w bazie i został przydzielony dostęp");
            errorFields.setVisibility(View.GONE);
            errorSite.setVisibility(View.GONE);
            progressBar.setVisibility(View.GONE);
            recyclerView.setVisibility(View.VISIBLE);

        } else {
            recyclerView.setVisibility(View.GONE);
            progressBar.setVisibility(View.GONE);
            errorFields.setVisibility(View.VISIBLE);
            errorSite.setVisibility(View.VISIBLE);
            imageView2.setVisibility(View.VISIBLE);
            errorSite.setText("Token zostął znaleziony w bazie, natomiast Administrator nie przydzielił dostępu do zasobu...");
            Log.e("Komunikat checkToken", "Token zostął znaleziony w bazie, natomiast na chwilę obecną nie został przydzielony dostęp przez Administratora");

        }

    }
}
