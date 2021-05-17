/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package com.mycompany.Views;

import com.codename1.components.InfiniteProgress;
import com.codename1.components.MultiButton;
import com.codename1.io.ConnectionRequest;
import com.codename1.io.NetworkManager;
import com.codename1.ui.Button;
import com.codename1.ui.CheckBox;
import com.codename1.ui.Component;
import com.codename1.ui.Container;
import com.codename1.ui.Dialog;
import com.codename1.ui.Display;
import com.codename1.ui.EncodedImage;
import com.codename1.ui.Form;
import com.codename1.ui.Image;
import com.codename1.ui.Label;
import com.codename1.ui.RadioButton;
import com.codename1.ui.URLImage;
import com.codename1.ui.events.ActionEvent;
import com.codename1.ui.layouts.BorderLayout;
import com.codename1.ui.layouts.BoxLayout;
import com.codename1.ui.plaf.UIManager;
import com.codename1.ui.util.Resources;
import com.mycompany.Models.Reponse;
import com.mycompany.Models.examen;
import com.mycompany.Models.question;
import com.mycompany.Services.Examen_Serive;
import com.mycompany.Services.Reponse_Service;
import com.mycompany.Services.question_service;

import com.mycompany.myapp.MyApplication;
import java.util.ArrayList;

/**
 *
 * @author user
 */
public class ExamenForm extends Form {
 public ArrayList<Reponse> Reponses = new ArrayList<>();
  public ArrayList<question> questions = new ArrayList<>();
 String Image_qr="";
    Resources theme = UIManager.initFirstTheme("/theme");
    int if_fo=0;
    public ExamenForm(Form previous,int id_f)
    {
      
           super("Examens",BoxLayout.y());
           
          
             if_fo=id_f;
        this.add(new InfiniteProgress());
        Display.getInstance().scheduleBackgroundTask(() -> {
            // this will take a while...

            Display.getInstance().callSerially(() -> {
                this.removeAll();
             for (examen c : new Examen_Serive().findAll(id_f)) {

            this.add(addItem_examen(c));

        }
             
                this.revalidate();
            });
        });

        this.getToolbar().addSearchCommand(e -> {
            String text = (String) e.getSource();
            if (text == null || text.length() == 0) {
                // clear search
                for (Component cmp : this.getContentPane()) {
                    cmp.setHidden(false);
                    cmp.setVisible(true);
                }
                this.getContentPane().animateLayout(150);
            } else {
                text = text.toLowerCase();
                for (Component cmp : this.getContentPane()) {
                    MultiButton mb = (MultiButton) cmp;
                    String line1 = mb.getTextLine1();
                    String line2 = mb.getTextLine2();
                    mb.setUIIDLine1("libC");
                    mb.setUIIDLine2("btn");
                    boolean show = line1 != null && line1.toLowerCase().indexOf(text) > -1
                            || line2 != null && line2.toLowerCase().indexOf(text) > -1;
                    mb.setHidden(!show);
                    mb.setVisible(show);
                }
                this.getContentPane().animateLayout(150);
            }
        }, 4);
             
               this.getToolbar().addCommandToOverflowMenu("back", null, ev -> {
           new MyApplication().start();
        });
    }
    
     public MultiButton  addItem_examen(examen c) {
        MultiButton m = new MultiButton();
         m.setTextLine1(c.getDescription());
         m.setIcon(theme.getImage("exam.png"));
             m.setEmblem(theme.getImage("round.png"));
             m.addActionListener(l
                -> {
                 
                 Form f= new Form ("Exam : "+c.getDescription() ,BoxLayout.y() );
                  question_service qs=     new question_service();
                  questions=qs.findAll(c.getId());
                    for (question q : new question_service().findAll(c.getId())) {

            f.add(addItem_question(q));

        }
                                Button btn = new Button("Valider");
                                f.add(btn);
                     btn.addActionListener((ActionEvent lqp)->{
                         
           
            
            if (qs.findAll(c.getId()).size() == Reponses.size())
            {
                int i=0;
                  for (Reponse w : Reponses) {

         if(w.getVrai().equals("yes"))
         {
             i++;
         }

        }
                   float score = (float)i/questions.size();
                 String urlab = "http://localhost/qrcode/qrcode.php";

                                ConnectionRequest cnreq = new ConnectionRequest();
                                cnreq.setPost(false);
                                String data = "";

                              
                  if (i == Reponses.size())
                  {
                      Examen_Serive es = new Examen_Serive();
                      es.sendmail(c.getId(), i);
                      data="Felicitations , vous avez reussi votre examen , votre score est "+Math.round(score*100)+"%";    
                  }
                  else
                  {
                      data="Malheuresement , vous avez echoue , votre score est "+Math.round(score*100) +"% < 80% ";
                         Examen_Serive es = new Examen_Serive();
                      es.sendmail(c.getId(), i);      
                  }
                  
                    cnreq.addArgument("data", data);
                                cnreq.setUrl(urlab);

                                cnreq.addResponseListener(evx
                                        -> {
                                     Image_qr = new String(cnreq.getResponseData());
                               

                                }
                                );
                                NetworkManager.getInstance().addToQueueAndWait(cnreq);
                  Reponses.clear();
                         Dialog.show("test valide", "test valide", "OK", null); 
                       
                        Form f3 = new Form("Qr code",BoxLayout.y());
                         String url = "http://localhost/qrcode/" + Image_qr;

                    Image imge;
                    EncodedImage enc;

                    enc = EncodedImage.createFromImage(theme.getImage("round.png"), false);
                    imge = URLImage.createToStorage(enc, url, url);
                    f3.add(imge);
                        
                           f3.getToolbar().addCommandToOverflowMenu("back", null, ev -> {
           new MyApplication().start();
        });
                        f3.show();
                  
            }
            else
            {
                  Dialog.show("Error", "Error", "OK", null);   
                    new ExamenForm(f, if_fo).show();
                     
            }
                    
                 
             
              
             
             });
                 
                  f.getToolbar().addCommandToOverflowMenu("back", null, ev -> {
           new MyApplication().start();
        });
                 f.show();

             });
        return m;
       
         }
     public Container addItem_question(question q) {

        Container cn1 = new Container(new BorderLayout());
        Container cn2 = new Container(BoxLayout.y());
        Label nom = new Label("question :"+q.getQues());
         
         
                
        

        cn2.add(nom).add("-------");
            for (Reponse qw : new Reponse_Service().findAll(q.getId())) {
                
                
RadioButton cb1 = new RadioButton(qw.getReponse());
cb1.addActionListener(apmq->{
    System.out.println(qw.getVrai());
Reponses.add(qw);
  
});
            cn2.add(cb1);
            
        
        }

             cn2.add("-------");
            
             
        cn1.add(BorderLayout.WEST, cn2);
        return cn1;
         }
       
}
